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

    public function finalizeAudit($restartApplication = true)
    {
        $this->module()->onAfterRequest();
        \Yii::getLogger()->flush(true);
        \Yii::$app->db->close();
        $this->destroyApplication();

        if ($restartApplication)
            $this->mockApplication();
    }

    public function useEntry($entry)
    {
        $reflection = new \ReflectionClass($this->module());
        $property = $reflection->getProperty('_entry');
        $property->setAccessible(true);
        $property->setValue($this->module(), $entry);
    }

    protected function tearDown()
    {
        \Yii::getLogger()->messages = [];
        parent::tearDown();
    }


}