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

    public function assertEqualsIgnoreLineBreakType($actual, $expected)
    {
        $expected = preg_replace('~\R~u', "\n", $expected);
        $actual = preg_replace('~\R~u', "\n", $actual);
        $this->assertEquals($actual, $expected);
    }

    protected function getMock($originalClassName, $methods = [], array $arguments = [], $mockClassName = '', $callOriginalConstructor = true, $callOriginalClone = true, $callAutoload = true, $cloneArguments = false, $callOriginalMethods = false, $proxyTarget = null)
    {
        //$this->warnings[] = 'PHPUnit_Framework_TestCase::getMock() is deprecated, use PHPUnit_Framework_TestCase::createMock() or PHPUnit_Framework_TestCase::getMockBuilder() instead';

        $mockObject = $this->getMockObjectGenerator()->getMock(
            $originalClassName,
            $methods,
            $arguments,
            $mockClassName,
            $callOriginalConstructor,
            $callOriginalClone,
            $callAutoload,
            $cloneArguments,
            $callOriginalMethods,
            $proxyTarget
        );

        $this->registerMockObject($mockObject);

        return $mockObject;
    }
}