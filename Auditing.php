<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit;

use yii\base\Application;
use bedezign\yii2\audit;

/**
 * Class Auditing
 * @package bedezign\yii2\audit
 *
 * Auditing main module.
 * This module is also responsible for starting the auditing process.
 * To configure it you need to do 2 things:
 * - add a module configuration entry:
 *     'modules' => [
 *        'audit' => 'bedezign\yii2\audit\Auditing',
 *     ]
 *   or optionally with configuration:
 *     'modules' => [
 *        'auditing' => [
 *            'class' => 'bedezign\yii2\audit\Auditing',
 *            'ignoreActions' => ['debug/*']
 *     ]
 * - If you want to auto track actions, be sure to add the module to the application bootstrapping:
 *    'bootstrap' => ['auditing'],
 *

 */
class Auditing extends \yii\base\Module
{
    /** @var string         name of the component to use for database access */
    public $db              = 'db';

    /** @var string[]       List of actions to track. '*' is allowed as the last character to use as wildcard. */
    public $trackActions    = ['*'];

    /** @var string[]       Actions to ignore. '*' is allowed as the last character to use as wildcard. */
    public $ignoreActions   = [];

    /** @var int            Chance in % that the truncate will run, false to not run at all */
    public $truncateChance  = false;

    /** @var int            Maximum age (in days) of the audit entries before they are truncated */
    public $maxAuditAge     = null;

    private static $current = null;

    /** @var audit\models\AuditEntry */
    private $entry = null;

    public function init()
    {
        static::$current = $this;

        parent::init();

        if ($this->truncateChance !== false && $this->maxAuditAge !== null) {
            if (rand(1, 100) <= $this->truncateChance)
                $this->truncate();
        }

        $this->trackActions  = \yii\helpers\ArrayHelper::toArray($this->trackActions);
        $this->ignoreActions = \yii\helpers\ArrayHelper::toArray($this->ignoreActions);

        //  Subscribe to event
        \Yii::$app->on(Application::EVENT_BEFORE_ACTION, [$this, 'onApplicationAction']);
    }

    public function onApplicationAction($event)
    {
        $actionId = $event->action->uniqueId;

        if (count($this->trackActions) && !$this->routeMatches($actionId, $this->trackActions))
            return;

        if (count($this->ignoreActions) && $this->routeMatches($actionId, $this->ignoreActions))
            return;

        // Still here, start auditing
        $this->getEntry(true);
    }


    /**
     * Associate extra data with the current entry (if any)
     * @param $data
     */
    public function data($data)
    {
        $entry = $this->getEntry(false);
        if (!$entry)
            return;

        $data = audit\components\Helper::compact($data);
    }

    /**
     * @return \yii\db\Connection the database connection.
     */
    public function getDb()
    {
        return \Yii::$app->{$this->db};
    }

    /**
     * @return static
     */
    public static function current()
    {
        return static::$current;
    }

    protected function getEntry($create = false)
    {
        if ($create && !$this->entry) {

            $this->entry = audit\models\AuditEntry::create(true);
            $this->entry->save(false);

            // We've started an entry, register a shutdown call to finalize it
            register_shutdown_function([$this, 'finalizeEntry']);
        }

        return $this->entry;
    }

    /**
     * Clean up the audit data according to the settings.
     * Can be handy if you are offloading the data somewhere and want to keep only the most recent entries readily available
     */
    protected function truncate()
    {

    }

    public function finalizeEntry()
    {
        if (!$this->entry)
            return true;

        return $this->entry->finalize();
    }

    /**
     * Verifies a route against a given list and returns whether it matches or not.
     * Entries in the list are allowed to end with a '*', which means that a substring will be used for the match
     * instead of a full compare.
     *
     * @param string    $route      An application rout
     * @param string[]  $list       List of routes to compare against.
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
}