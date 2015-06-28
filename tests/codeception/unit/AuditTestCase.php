<?php

namespace tests\codeception\unit;

class AuditTestCase extends \yii\codeception\TestCase
{
    /**
     * @return \bedezign\yii2\audit\Audit
     */
    public function module()
    {
        return \Yii::$app->getModule('audit');
    }

    public function entry($create = true, $new = false)
    {
        return $this->module()->getEntry($create, $new);
    }

    public function finalizeAudit()
    {
        $this->module()->onAfterRequest();
        $this->module()->logTarget->collect([], true);
    }
}