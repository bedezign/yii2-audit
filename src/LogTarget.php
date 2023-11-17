<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace bedezign\yii2\audit;

use Yii;
use yii\log\Target;

/**
 * LogTarget
 * @package bedezign\yii2\audit
 */
class LogTarget extends Target
{
    /**
     * @var Audit
     */
    public $module;

    /**
     * @var bool Set to true to allow Audit to export the logs during the handling of the request according to $exportInterval.
     * By default this is disabled, as it will cause extra DB load and possibly slow down the response time.
     * If you however expect to do an obscene amount of logging you can enable this.
     */
    public $exportAtIntervals = false;

    /**
     * @param Audit $module
     * @param array $config
     */
    public function __construct($module, $config = [])
    {
        parent::__construct($config);
        $this->module = $module;
    }

    /**
     *
     */
    public function export()
    {
        if (!\Yii::$app)
            // Things like this can happen in tests etc, but it is too late for us to do anything about that.
            return;

        $module = $this->module;
        if (!$module->entry || empty($module->panels))
            return;

        $entry = $module->entry;

        $records = [];
        foreach ($module->panels as $id => $panel) {
            $records[$id] = $panel->save();
        }
        $records = array_filter($records);

        if (!empty($records)) {
            if ($module->batchSave)
                $entry->addBatchData($records, false);
            else {
                foreach ($records as $type => $record)
                    $entry->addData($type, $record, false);
            }
        }
    }

    /**
     * @param array $messages
     * @param bool  $final
     */
    public function collect($messages, $final)
    {
        if ($this->exportAtIntervals) {
            parent::collect($messages, $final);
        }
        else {
            $this->messages = array_merge($this->messages, static::filterMessages($messages, $this->getLevels(), $this->categories, $this->except));
            if ($final) {
                $this->export();
                $this->messages = [];
            }
        }
    }
}
