<?php

namespace tests\codeception\unit;

use yii\db\ActiveRecord;

class AuditTestCase extends \Codeception\Test\Unit
{
    /**
     * @return \bedezign\yii2\audit\Audit
     */
    public function module()
    {
        return $this->getModule('Yii2')->client->getApplication()->getModule('audit');
    }

    public function entry($create = true, $new = false)
    {
        return $this->module()->getEntry($create, $new);
    }

    public function finalizeAudit($restartApplication = true)
    {
        /** @var \Codeception\Lib\Connector\Yii2 $connector */
        $connector = $this->getModule('Yii2')->client;
        $app = $connector->getApplication();

        // Trigger event handler
        $this->module()->onAfterRequest();

        // The globally used logger is not the same as the one for the application because the connectors' startApplication()
        // function replaces the global instance with its last line of code. So make sure we flush the application one
        $app->getLog()->getLogger()->flush(true);

        // Terminate application
        $app->db->close();
        $connector->resetApplication();
        $connector->resetPersistentVars();

        if ($restartApplication) {
            $connector->getApplication();
        }
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