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
     * @var array list of panels.
     * If the value is a simple string, it is the identifier of an internal to activate (with default settings)
     * If the entry is a '<key>' => '<string>|<array>' it is a new panel. It can optionally override a core panel or add a new one.
     */
    public $panels = [
        'request',
        'error',
        'db',
        'log',
        'mail',
        'profiling',
        // 'asset',
        // 'config',
    ];

    /**
     * @var AuditTarget
     */
    public $logTarget;

    /**
     * @var array
     */
    private $_corePanels = [
        'audit/request' => ['class' => 'bedezign\yii2\audit\panels\RequestPanel'],
        'audit/error' => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
        'audit/db' => ['class' => 'bedezign\yii2\audit\panels\DbPanel'],
        'audit/log' => ['class' => 'bedezign\yii2\audit\panels\LogPanel'],
        'audit/asset' => ['class' => 'bedezign\yii2\audit\panels\AssetPanel'],
        'audit/config' => ['class' => 'bedezign\yii2\audit\panels\ConfigPanel'],
        'audit/mail' => ['class' => 'bedezign\yii2\audit\panels\MailPanel'],
        'audit/profiling' => ['class' => 'bedezign\yii2\audit\panels\ProfilingPanel'],
    ];

    /**
     * @var array
     */
    private $_viewOnlyPanels = [
        'audit/errors' => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
        'audit/javascript' => ['class' => 'bedezign\yii2\audit\panels\JavascriptPanel'],
        'audit/trail' => ['class' => 'bedezign\yii2\audit\panels\TrailPanel'],
        'audit/extra' => ['class' => 'bedezign\yii2\audit\panels\ExtraDataPanel'],
    ];

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
        $this->logTarget = $app->getLog()->targets['audit'] = new AuditTarget($this);
        $this->initPanels();
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
     * Associate extra data with the current entry (if any)
     * @param string $type Optional type argument
     * @param mixed $data The data to associate with the current entry
     * @return models\AuditData
     */
    public function data($type, $data)
    {
        $this->getEntry(true); // force create of an entry to store the data

        if (!isset($this->panels['audit/extra']))
            $this->panels['audit/extra'] = Yii::createObject(['class' => 'bedezign\yii2\audit\panels\ExtraDataPanel']);

        $this->panels['audit/extra']->trackData(['type' => $type, 'data' => $data]);
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
     * @return models\AuditEntry|static
     */
    public function getEntry($create = false)
    {
        if (!$this->_entry && $create) {
            $this->_entry = models\AuditEntry::create(true);
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
     * @param bool $all
     */
    public function initPanels($all = false)
    {
        $panels = $this->getPanels();

        if ($all) {
            foreach ($this->_viewOnlyPanels as $identifier => $config) {
                if (!isset($panels[$identifier])) {
                    $panels[$identifier] = Yii::createObject($config);
                }
            }
        }

        $this->panels = $panels;
    }

    /**
     * @return array
     */
    protected function getPanels()
    {
        $panels = [];
        foreach ($this->panels as $key => $value) {
            list($identifier, $config) = $this->getPanelConfig($key, $value);
            if (is_array($config)) {
                $config['module'] = $this;
                $config['id'] = $identifier;
                $panels[$identifier] = Yii::createObject($config);
            } else {
                $panels[$identifier] = $config;
            }
        }
        return $panels;
    }

    /**
     * @param $key
     * @param $value
     * @return array
     * @throws InvalidConfigException
     */
    protected function getPanelConfig($key, $value)
    {
        $identifier = $config = null;
        if (is_numeric($key)) {
            // The config a panel name
            if (strpos($value, '/') === false) $value = 'audit/' . $value;
            if (!isset($this->_corePanels[$value]))
                throw new InvalidConfigException("'$value' is not a valid panel name");
            $identifier = $value;
            $config = $this->_corePanels[$value];
        } elseif (is_string($key)) {
            $identifier = $key;
            $config = is_string($value) ? ['class' => $value] : $value;
        }
        return [$identifier, $config];
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
            } else {
                /** @var Audit $module */
                $class = $module::className();
            }

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
