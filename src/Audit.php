<?php
/**
 * This serves as both the Module for the MVC part of the audit and the configuration/entry point for the actual
 * audit process.
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @copyright 2014-2015 B&E DeZign
 */

namespace bedezign\yii2\audit;

use bedezign\yii2\audit\models\AuditEntry;
use Yii;
use yii\base\ActionEvent;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\debug\Panel;
use yii\helpers\ArrayHelper;

/**
 * Audit main module.
 *
 * This module is also responsible for starting the audit process.
 * To configure it you need to do 2 things:
 * - add a module configuration entry:
 *     'modules' => [
 *        'audit' => 'bedezign\yii2\audit\Audit',
 *     ]
 *   or optionally with configuration:
 *     'modules' => [
 *        'audit' => [
 *            'class' => 'bedezign\yii2\audit\Audit',
 *            'ignoreActions' => ['debug/*']
 *     ]
 * - If you want to auto track actions, be sure to add the module to the application bootstrapping:
 *    'bootstrap' => ['audit'],
 *
 * @package bedezign\yii2\audit
 * @property AuditEntry $entry
 */
class Audit extends Module
{
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout = 'main';

    /**
     * @var string name of the component to use for database access
     */
    public $db = 'db';

    /**
     * @var string[] Action or list of actions to track. '*' is allowed as the last character to use as wildcard.
     */
    public $trackActions = ['*'];

    /**
     * @var string[] Action or list of actions to ignore. '*' is allowed as the last character to use as wildcard (eg 'debug/*').
     */
    public $ignoreActions = [];

    /**
     * @var int Maximum age (in days) of the audit entries before they are truncated
     */
    public $maxAge = null;

    /**
     * @var string[] IP address or list of IP addresses with access to the viewer, null for everyone (if the IP matches)
     * An IP address can contain the wildcard `*` at the end so that it matches IP addresses with the same prefix.
     * For example, '192.168.*' matches all IP addresses in the segment '192.168.'.
     */
    public $accessIps = null;

    /**
     * @var string[] Role or list of roles with access to the viewer, null for everyone (if the user matches)
     */
    public $accessRoles = ['admin'];

    /**
     * @var int[] User ID or list of user IDs with access to the viewer, null for everyone (if the role matches)
     */
    public $accessUsers = null;

    /**
     * @var bool Compress extra data generated or just keep in text? For people who don't like binary data in the DB
     */
    public $compressData = true;

    /**
     * @var string The callback to use to convert a user id into an identifier (username, email, ...). Can also be html.
     */
    public $userIdentifierCallback = false;

    /**
     * @var string Will be called to translate text in the user filter into a (or more) user id's
     */
    public $userFilterCallback = false;

    /**
     * @var array list of panels that should be active/tracking/available during the auditing phase.
     * If the value is a simple string, it is the identifier of an internal panel to activate (with default settings)
     * If the entry is a '<key>' => '<string>|<array>' it is a new panel. It can optionally override a core panel or add a new one.
     * It is important that the key is unique, as this is the identifier used to store any data associated with the panel.
     *
     * Please note: If you add custom panels, please namespace them ("mynamespace/panelname").
     * Any non-namespaced identifier will be looked for in the `audit` namespace.
     */
    public $panels = [
        'request',
        'db',
        'log',
        'mail',
        'profiling',
        // 'asset',
        // 'config',

        // These provide special functionality and get loaded to activate it
        'error',      // Links the extra error reporting functions (`exception()` and `errorMessage()`)
        'extra'       // Links the data functions (`data()`)
    ];

    /**
     * @var LogTarget
     */
    public $logTarget;

    /**
     * @var array
     */
    private $_corePanels = [
        // Tracking/logging panels
        'audit/request'    => ['class' => 'bedezign\yii2\audit\panels\RequestPanel'],
        'audit/db'         => ['class' => 'bedezign\yii2\audit\panels\DbPanel'],
        'audit/log'        => ['class' => 'bedezign\yii2\audit\panels\LogPanel'],
        'audit/asset'      => ['class' => 'bedezign\yii2\audit\panels\AssetPanel'],
        'audit/config'     => ['class' => 'bedezign\yii2\audit\panels\ConfigPanel'],
        'audit/mail'       => ['class' => 'bedezign\yii2\audit\panels\MailPanel'],
        'audit/profiling'  => ['class' => 'bedezign\yii2\audit\panels\ProfilingPanel'],

        // Special other panels
        'audit/error'      => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
        'audit/javascript' => ['class' => 'bedezign\yii2\audit\panels\JavascriptPanel'],
        'audit/trail'      => ['class' => 'bedezign\yii2\audit\panels\TrailPanel'],
        'audit/mail'       => ['class' => 'bedezign\yii2\audit\panels\MailPanel'],
        'audit/extra'      => ['class' => 'bedezign\yii2\audit\panels\ExtraDataPanel'],
    ];

    private $_panelFunctions = [];

    /**
     * @var \bedezign\yii2\audit\models\AuditEntry If activated this is the active entry
     */
    private $_entry = null;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $app = Yii::$app;

        // Before action triggers a new audit entry
        $app->on(Application::EVENT_BEFORE_ACTION, [$this, 'onBeforeAction']);
        // After request finalizes the audit entry.
        $app->on(Application::EVENT_AFTER_REQUEST, [$this, 'onAfterRequest']);

        // Activate the logging target
        $this->logTarget = $app->getLog()->targets['audit'] = new LogTarget($this);

        // Boot all active panels
        $this->normalizePanelConfiguration();
        $this->panels = $this->loadPanels(array_keys($this->panels));
    }

    /**
     * Called to evaluate if the current request should be logged
     * @param ActionEvent $event
     */
    public function onBeforeAction($event)
    {
        if (!empty($this->trackActions) && !$this->routeMatches($event->action->uniqueId, $this->trackActions)) {
            return;
        }
        if (!empty($this->ignoreActions) && $this->routeMatches($event->action->uniqueId, $this->ignoreActions)) {
            return;
        }
        // Still here, start audit
        $this->getEntry(true);
    }

    /**
     *
     */
    public function onAfterRequest()
    {
        if ($this->_entry) {
            $this->_entry->finalize();
        }
    }

    /**
     * Allows panels to register functions that can be called directly on the module
     * @param string    $name
     * @param callable  $callback
     */
    public function registerFunction($name, $callback)
    {
        $this->_panelFunctions[$name] = $callback;
    }

    public function __call($name, $params)
    {
        if (!isset($this->_panelFunctions[$name]))
            throw new \yii\base\InvalidCallException("Unknown panel function '$name'");

        return call_user_func_array($this->_panelFunctions[$name], $params);
    }

    /**
     * @return \yii\db\Connection the database connection.
     */
    public function getDb()
    {
        return Yii::$app->{$this->db};
    }

    /**
     * @param bool $create
     * @param bool $new
     * @return AuditEntry|static
     */
    public function getEntry($create = false, $new = false)
    {
        if ((!$this->_entry && $create) || $new) {
            $this->_entry = AuditEntry::create(true);
        }
        return $this->_entry;
    }

    /**
     * @param $user_id
     * @return string
     */
    public function getUserIdentifier($user_id)
    {
        if (!$user_id) {
            return Yii::t('audit', 'Guest');
        }
        if ($this->userIdentifierCallback && is_callable($this->userIdentifierCallback)) {
            return call_user_func($this->userIdentifierCallback, $user_id);
        }
        return $user_id;
    }

    /**
     * Returns a list of all available panel identifiers
     * @return string[]
     */
    public function getPanelIdentifiers()
    {
        return array_unique(array_merge(array_keys($this->panels), array_keys($this->_corePanels)));
    }

    /**
     * Tries to assemble the configuration for the panels that the user wants for auditing
     * @param string[]          Set of panel identifiers that should be loaded
     * @return Panel[]
     */
    public function loadPanels($list)
    {
        $panels = [];
        foreach ($list as $panel) {
            $panels[$panel] = $this->getPanel($panel);
        }
        return $panels;
    }

    /**
     * @param string $identifier
     * @return null|Panel
     * @throws InvalidConfigException
     */
    public function getPanel($identifier)
    {
        $config = null;
        if (isset($this->panels[$identifier]))
            $config = $this->panels[$identifier];
        elseif (isset($this->_corePanels[$identifier]))
            $config = $this->_corePanels[$identifier];

        if (!$config)
            throw new InvalidConfigException("'$value' is not a valid panel identifier");

        if (is_array($config)) {
            $config['module'] = $this;
            $config['id'] = $identifier;
            return Yii::createObject($config);
        }

        return $config;
    }

    /**
     * Make sure the configured panels array is a uniform set of <identifier> => <config> entries.
     * @throws InvalidConfigException
     */
    protected function normalizePanelConfiguration()
    {
        $panels = [];
        foreach ($this->panels as $id => $panel) {
            if (is_numeric($id)) {
                // The value is a panel ID. If not namespaced then we assume a core panel
                if (strpos($panel, '/') === false) $panel = 'audit/' . $panel;
                if (!isset($this->_corePanels[$panel]))
                    throw new InvalidConfigException("'$panel' is not a valid panel identifier");
                $panels[$panel] = $this->_corePanels[$panel];
            }
            else {
                $panels[$id] = is_string($panel) ? ['class' => $panel] : $panel;
            }
        }
        $this->panels = $panels;
    }

    /**
     * @return int|null|string
     */
    public static function findModuleIdentifier()
    {
        foreach (Yii::$app->modules as $name => $module) {
            $class = null;
            if (is_string($module))
                $class = $module;
            elseif (is_array($module)) {
                if (isset($module['class']))
                    $class = $module['class'];
            } else
                /** @var Module $module */
                $class = $module::className();

            $parts = explode('\\', $class);
            if ($class && strtolower(end($parts)) == 'audit')
                return $name;
        }
        return null;
    }

    /**
     * Verifies a route against a given list and returns whether it matches or not.
     * Entries in the list are allowed to end with a '*', which means that a substring will be used for the match
     * instead of a full compare.
     *
     * @param string $route An application rout
     * @param string[] $list List of routes to compare against.
     * @return bool
     */
    protected function routeMatches($route, $list)
    {
        $list = ArrayHelper::toArray($list);
        foreach ($list as $compare) {
            $len = strlen($compare);
            if ($compare[$len - 1] == '*') {
                $compare = rtrim($compare, '*');
                if (substr($route, 0, $len - 1) === $compare)
                    return true;
            }

            if ($route === $compare)
                return true;
        }
        return false;
    }

}
