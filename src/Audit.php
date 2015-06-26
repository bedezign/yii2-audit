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
     * @var static The current instance
     */
    private static $_current = null;

    /**
     * @var \bedezign\yii2\audit\models\AuditEntry If activated this is the active entry
     */
    private $_entry = null;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        self::$_current = $this;
        parent::init();

        $app = Yii::$app;

        // Allow the users to specify a simple string if there is only 1 entry
        $this->trackActions = ArrayHelper::toArray($this->trackActions);
        $this->ignoreActions = ArrayHelper::toArray($this->ignoreActions);

        if (!empty($this->accessRoles))
            $this->accessRoles = ArrayHelper::toArray($this->accessRoles);

        if (!empty($this->accessUsers))
            $this->accessUsers = ArrayHelper::toArray($this->accessUsers);

        if (!empty($this->accessIps))
            $this->accessIps = ArrayHelper::toArray($this->accessIps);

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
     * @param \yii\base\Event $event
     */
    public function onBeforeAction($event)
    {
        $actionId = $event->action->uniqueId;

        if (count($this->trackActions) && !$this->routeMatches($actionId, $this->trackActions))
            return;

        if (count($this->ignoreActions) && $this->routeMatches($actionId, $this->ignoreActions))
            return;

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
        $entry = $this->entry;
        if (!$entry) {
            return;
        }

        if (!isset($this->panels['audit/custom']))
            $this->panels['audit/custom'] = Yii::createObject(['class' => 'bedezign\yii2\audit\panels\CustomDataPanel']);

        $this->panels['audit/custom']->trackData(['type' => $type, 'data' => $data]);
    }

    /**
     * @return \yii\db\Connection the database connection.
     */
    public function getDb()
    {
        return Yii::$app->{$this->db};
    }

    /**
     * Check if the current user has access to the audit functionality
     * @return bool
     * @throws InvalidConfigException
     */
    public function checkAccess()
    {
        if ($this->accessIps === null && $this->accessRoles === null && $this->accessUsers === null) {
            return true;
        }
        if ($this->checkAccessIps()) {
            return true;
        }
        if ($this->checkAccessRoles()) {
            return true;
        }
        if ($this->checkAccessUsers()) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function checkAccessUsers()
    {
        if (!empty($this->accessUsers) && in_array(Yii::$app->user->id, $this->accessUsers))
            return true;
        return false;
    }

    /**
     * @return bool
     */
    private function checkAccessRoles()
    {
        $user = \yii\di\Instance::ensure('user', \yii\web\User::className());
        if (empty($this->accessRoles)) {
            return false;
        }
        foreach ($this->accessRoles as $role) {
            if ($role === '?' && $user->getIsGuest()) {
                return true;
            } elseif ($role === '@' && !$user->getIsGuest()) {
                return true;
            } elseif ($user->can($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    private function checkAccessIps()
    {
        if (!empty($this->accessIps) && in_array(Yii::$app->request->getUserIP(), $this->accessIps))
            return true;
        return false;
    }

    /**
     * @return array
     */
    public function getAccessControlFilter()
    {
        if ($this->accessUsers === null && $this->accessRoles === null && $this->accessIps === null) {
            // No user authentication active, skip adding the filter
            return [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [['allow' => true]]
            ];
        }

        $rule = ['allow' => 'allow'];

        if (!empty($this->accessIps)) {
            // Add allowed ips
            $rule['ips'] = $this->accessIps;
        }

        if (!empty($this->accessRoles)) {
            // Add allowed roles
            $rule['roles'] = $this->accessRoles;
        }

        if (!empty($this->accessUsers)) {
            $users = $this->accessUsers;
            // Specific users only? Use callback
            $rule['matchCallback'] = function () use ($users) {
                return in_array(Yii::$app->user->id, $users);
            };
        }

        return ['class' => \yii\filters\AccessControl::className(), 'rules' => [$rule]];
    }

    /**
     * Returns the current module instance.
     * @return static
     */
    public static function current()
    {
        if (!self::$_current) {
            self::$_current = Yii::$app->getModule(Audit::findModuleIdentifier());
        }
        return self::$_current;
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

        try {
            if ($this->userIdentifierCallback && is_callable($this->userIdentifierCallback)) {
                return call_user_func($this->userIdentifierCallback, $user_id);
            }
        } catch (\Exception $e) {
            // if exception then just let it slide, we'll just return the user_id
        }
        return $user_id;
    }

    /**
     * @param bool $all
     * @throws InvalidConfigException
     */
    public function initPanels($all = false)
    {
        $panels = $this->getPanels();

        if ($all) {
            $viewOnlyPanels = [
                'audit/errors' => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
                'audit/javascript' => ['class' => 'bedezign\yii2\audit\panels\JavascriptPanel'],
                'audit/trail' => ['class' => 'bedezign\yii2\audit\panels\TrailPanel'],
                'audit/custom' => ['class' => 'bedezign\yii2\audit\panels\CustomDataPanel'],
            ];

            foreach ($viewOnlyPanels as $identifier => $config)
                if (!isset($panels[$identifier]))
                    $panels[$identifier] = Yii::createObject($config);
        }

        $this->panels = $panels;
    }

    /**
     * @return array
     * @throws InvalidConfigException
     */
    protected function getPanels()
    {
        $panels = [];
        $corePanels = $this->corePanels();
        foreach ($this->panels as $key => $value) {
            $identifier = $config = null;
            if (is_numeric($key)) {
                // The config a panel name
                if (strpos($value, '/') === false) $value = 'audit/' . $value;
                if (!isset($corePanels[$value]))
                    throw new InvalidConfigException("'$value' is not a valid panel name");
                $identifier = $value;
                $config = $corePanels[$value];
            } elseif (is_string($key)) {
                $identifier = $key;
                $config = is_string($value) ? ['class' => $value] : $value;
            }

            if (is_array($config)) {
                $config['module'] = $this;
                $config['id'] = $identifier;
                $panels[$identifier] = Yii::createObject($config);
            } else
                $panels[$identifier] = $config;
        }
        return $panels;
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

    /**
     * @return array
     */
    protected function corePanels()
    {
        return [
            'audit/request' => ['class' => 'bedezign\yii2\audit\panels\RequestPanel'],
            'audit/error' => ['class' => 'bedezign\yii2\audit\panels\ErrorPanel'],
            'audit/db' => ['class' => 'bedezign\yii2\audit\panels\DbPanel'],
            'audit/log' => ['class' => 'bedezign\yii2\audit\panels\LogPanel'],
            'audit/asset' => ['class' => 'bedezign\yii2\audit\panels\AssetPanel'],
            'audit/config' => ['class' => 'bedezign\yii2\audit\panels\ConfigPanel'],
            'audit/mail' => ['class' => 'bedezign\yii2\audit\panels\MailPanel'],
            'audit/profiling' => ['class' => 'bedezign\yii2\audit\panels\ProfilingPanel'],
        ];
    }

}
