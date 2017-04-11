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

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditEntry;
use Yii;
use yii\base\ActionEvent;
use yii\base\Application;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
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
 *
 * @method void data($type, $data)                                                                      @see ExtraDataPanel::trackData()
 * @method \bedezign\yii2\audit\models\AuditError exception(\Exception $exception)                      @see ErrorPanel::log()
 * @method \bedezign\yii2\audit\models\AuditError errorMessage($message, $code, $file, $line, $trace)   @see ErrorPanel::logMessage()
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
     * @var string[] Action or list of actions to track. '*' is allowed as the first or last character to use as wildcard.
     */
    public $trackActions = ['*'];

    /**
     * @var string[] Action or list of actions to ignore. '*' is allowed as the first or last character to use as wildcard (eg 'debug/*').
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
     * @var string The callback to get a user id.
     */
    public $userIdCallback = false;

    /**
     * @var string Will be called to translate text in the user filter into a (or more) user id's
     */
    public $userFilterCallback = false;

    /**
     * @var bool The module does batch saving of the data records by default. You can disable this if you are experiencing
     * `max_allowed_packet` errors when logging huge data quantities. Records will be saved per piece instead of all at once
     */
    public $batchSave = true;

    /**
     * @var array Default log levels to filter and process
     */
    public $logConfig = ['levels' => ['error', 'warning', 'info', 'profile']];


    /**
     * @var array|Panel[] list of panels that should be active/tracking/available during the auditing phase.
     * If the value is a simple string, it is the identifier of an internal panel to activate (with default settings)
     * If the entry is a '<key>' => '<string>|<array>' it is either a new panel or a panel override (if you specify a core id).
     * It is important that the key is unique, as this is the identifier used to store any data associated with the panel.
     *
     * Please note:
     * - If you just want to change the configuration for a core panel, use the `$panelConfiguration`, it will be merged into this one
     * - If you add custom panels, please namespace them ("mynamespace/panelname").
     */
    public $panels = [
        'audit/request',
        'audit/db',
        'audit/log',
        'audit/mail',
        'audit/profiling',
        'audit/trail',
        'audit/javascript',
        // 'audit/asset',
        // 'audit/config',

        // These provide special functionality and get loaded to activate it
        'audit/error',      // Links the extra error reporting functions (`exception()` and `errorMessage()`)
        'audit/extra',      // Links the data functions (`data()`)
        'audit/curl',       // Links the curl tracking function (`curlBegin()`, `curlEnd()` and `curlExec()`)
    ];

    /**
     * Everything you add in here will be merged with the basic panel configuration.
     * This gives you an easy way to just add or modify panels/configurations without having to re-specify every panel.
     * This only accepts regular definitions ('<key>' => '<array>'), but the core class will be added if needed
     * Take a look at the [module configuration](docs/module-configuration.md) for more information.
     */
    public $panelsMerge = [];

    /**
     * @var LogTarget
     */
    public $logTarget;

    /**
     * @see \yii\debug\Module::$traceLine
     */
    public $traceLine = \yii\debug\Module::DEFAULT_IDE_TRACELINE;

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
        'audit/profiling'  => ['class' => 'bedezign\yii2\audit\panels\ProfilingPanel'],

        // Special other panels
        'audit/error'      => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
        'audit/javascript' => ['class' => 'bedezign\yii2\audit\panels\JavascriptPanel'],
        'audit/trail'      => ['class' => 'bedezign\yii2\audit\panels\TrailPanel'],
        'audit/mail'       => ['class' => 'bedezign\yii2\audit\panels\MailPanel'],
        'audit/extra'      => ['class' => 'bedezign\yii2\audit\panels\ExtraDataPanel'],
        'audit/curl'       => ['class' => 'bedezign\yii2\audit\panels\CurlPanel'],
        'audit/soap'       => ['class' => 'bedezign\yii2\audit\panels\SoapPanel'],
    ];

    /**
     * @var array
     */
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

        // check if the module has been installed (prevents errors while installing)
        try {
            $this->getDb()->getTableSchema(AuditEntry::tableName());
        } catch (\Exception $e) {
            return;
        }

        // Before action triggers a new audit entry
        $app->on(Application::EVENT_BEFORE_ACTION, [$this, 'onBeforeAction']);
        // After request finalizes the audit entry.
        $app->on(Application::EVENT_AFTER_REQUEST, [$this, 'onAfterRequest']);

        // Activate the logging target
        if (empty($app->getLog()->targets['audit'])) {
            $this->logTarget = $app->getLog()->targets['audit'] = new LogTarget($this, $this->logConfig);
        } else {
            $this->logTarget = $app->getLog()->targets['audit'];
        }

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
        if (isset($this->_panelFunctions[$name]))
            throw new InvalidParamException("The '$name'-function has already been defined.");

        $this->_panelFunctions[$name] = $callback;
    }

    /**
     * @param \yii\debug\Panel $panel
     */
    public function registerPanel(\yii\debug\Panel $panel)
    {
        $this->panels[$panel->id] = $panel;
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed
     */
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
     * @return AuditEntry
     */
    public function getEntry($create = false, $new = false)
    {
        $entry = new AuditEntry();
        $tableSchema = $entry->getDb()->schema->getTableSchema($entry->tableName());
        if ($tableSchema) {
            if ((!$this->_entry && $create) || $new) {
                $this->_entry = AuditEntry::create(true);
            }
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
     * @return int|mixed|null|string
     */
    public function getUserId()
    {
        if ($this->userIdCallback && is_callable($this->userIdCallback)) {
            return call_user_func($this->userIdCallback);
        }
        return (Yii::$app instanceof \yii\web\Application && Yii::$app->user) ? Yii::$app->user->id : null;
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
            throw new InvalidConfigException("'$identifier' is not a valid panel identifier");

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
        foreach ($this->panels as $key => $value) {
            if (is_numeric($key)) {
                // The $value contains the identifier of a core panel
                if (!isset($this->_corePanels[$value]))
                    throw new InvalidConfigException("'$value' is not a valid panel identifier");
                $panels[$value] = $this->_corePanels[$value];
            }
            else {
                // The key contains the identifier and the value is either a class name or a full array
                $panels[$key] = is_string($value) ? ['class' => $value] : $value;
            }
        }
        $this->panels = ArrayHelper::merge($panels, $this->panelsMerge);

        // We now need one more iteration to add core classes to the panels added via the merge, if needed
        array_walk($this->panels, function(&$value, $key) {
           if (!isset($value['class'])) {
               if (isset($this->_corePanels[$key]))
                   $value = ArrayHelper::merge($value, $this->_corePanels[$key]);
               else
                   throw new InvalidConfigException("Invalid configuration for '$key'. No 'class' specified.");
           }
        });
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
     * @param string $className
     * @return bool|string
     */
    public static function findPanelIdentifier($className)
    {
        $audit = Audit::getInstance();
        foreach ($audit->panels as $panel) {
            if ($panel->className() == $className) {
                return $panel->id;
            }
        }
        return false;
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

            if ($compare[0] == '*') {
                $compare = ltrim($compare, '*');
                if (substr($route, - ($len - 1)) === $compare)
                    return true;
            }

            if ($route === $compare)
                return true;
        }
        return false;
    }

}
