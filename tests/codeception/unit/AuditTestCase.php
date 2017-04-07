<?php

namespace tests\codeception\unit;

use yii\db\ActiveRecord;

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

    public function assertEqualsIgnoreLineBreakType($actual, $expected)
    {
        $expected = preg_replace('~\R~u', "\n", $expected);
        $actual = preg_replace('~\R~u', "\n", $actual);
        $this->assertEquals($actual, $expected);
    }


    public function getLastPk($modelName)
    {
        /** @var ActiveRecord $model */
        $model = new $modelName;
        $model = $model->find()->orderBy([$model->primaryKey()[0] => SORT_DESC])->one();
        return $model ? $model->primaryKey : 0;
    }

}