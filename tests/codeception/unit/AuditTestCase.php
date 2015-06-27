<?php

namespace tests\codeception\unit;

class AuditTestCase extends \yii\codeception\TestCase
{
    public function module()
    {
        return \Yii::$app->getModule('audit');
    }

    public function entry($create = true)
    {
        return $this->module()->getEntry($create);
    }

    public function finalizeAudit()
    {
        $this->module()->onAfterRequest();
        $this->module()->logTarget->collect([], true);
    }
}