<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\components;

use bedezign\yii2\audit\models\AuditEntry;
use yii\base\Component;

class Auditing extends Component
{
    /** @var string         name of the component to use for database access */
    public $db              = 'db';

    /** @var bool           Set to true and then autoload the component to add an entry for every request. With false only
     *                      errors will be logged. */
    public $trackAll        = true;

    /** @var int            Chance in % that the truncate will run, false to not run at all */
    public $truncateChance  = false;

    /** @var int            Maximum age (in days) of the audit entries before they are truncated */
    public $maxAuditAge     = null;

    private static $current = null;

    /** @var AuditEntry */
    private $entry = null;

    public function init()
    {
        static::$current = $this;

        parent::init();

        if ($this->truncateChance !== false && $this->maxAuditAge !== null) {
            if (rand(0, 100) == $this->truncateChance)
                $this->truncate();
        }

        if ($this->trackAll)
            $this->getEntry();
    }

    public function getEntry($create = true)
    {
        if ($create && !$this->entry) {
            $this->entry = AuditEntry::create(true, $this);
            $this->entry->save(false);
            register_shutdown_function([$this, 'finalizeEntry']);
        }

        return $this->entry;
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

        $data = Helper::compact($data);
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

    /**
     * Clean up the audit data according to the settings.
     * Can be handy if you are offloading the data somewhere and want to keep only the most recent entries readily available
     */
    protected function truncate()
    {

    }

    protected function finalizeEntry()
    {
        if (!$this->entry)
            return;

        //if ($this->entry-)
    }
}