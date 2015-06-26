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
 * Class AuditTarget
 * @package bedezign\yii2\audit
 */
class AuditTarget extends Target
{
    /**
     * @var Audit
     */
    public $module;

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

        if (!$this->module->entry || empty($this->module->panels))
            return;

        $records = [];
        foreach ($this->module->panels as $id => $panel) {
            $records[$id] = $panel->save();
        }

        $records = array_filter($records);
        if (!empty($records))
            $this->module->entry->addBatchData($records, false);
    }

    /**
     * @param array $messages
     * @param bool  $final
     */
    public function collect($messages, $final)
    {
        $this->messages = array_merge($this->messages, $messages);
        if ($final) {
            $this->export();
        }
    }

}
