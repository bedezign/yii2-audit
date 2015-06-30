<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;
use Yii;
use yii\db\Exception;

/**
 * AuditErrorTest
 */
class AuditErrorTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testGetEntry()
    {
        $error = AuditError::findOne(1);
        $this->assertEquals($error->getEntry()->one()->className(), AuditEntry::className());
    }

    public function testAddManualError()
    {
        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        $entry = $this->entry();
        AuditError::logMessage($entry, 'This is an unexpected error!', 1234, 'test.php', 50);

        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertEquals($oldId + 1, $newId, 'Expected error entry to be created');

        $this->assertInstanceOf(AuditError::className(), $error = AuditError::findOne($newId));
        $this->assertEquals('This is an unexpected error!', $error->message);
        $this->assertEquals(1234, $error->code);
        $this->assertEquals('test.php', $error->file);
        $this->assertEquals(50, $error->line);
    }

    public function testException()
    {
        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        $exception = new Exception('This is a test error!');
        Yii::$app->errorHandler->logException($exception);

        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertNotEquals($oldId, $newId, 'Expected error entry to be created');
    }

    public function testExceptionOutOfMemory()
    {
        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        $exception = new Exception('Allowed memory size of ...');
        Yii::$app->errorHandler->logException($exception);

        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertEquals($oldId, $newId, 'Expected error entry to not be created');
    }

    public function testModuleCannotNotLoadDoesntLog()
    {
        $module = Audit::getInstance();

        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        Audit::setInstance(null);
        Yii::$app->setModule('audit', null);

        $exception = new Exception('Unexpected error triggered before bootstrap');
        Yii::$app->errorHandler->logException($exception);

        // Restore module so the getDb() calls work again
        Audit::setInstance($module);
        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertEquals($oldId, $newId, 'Expected error entry to not be created');
    }

    public function testModuleIsAutoloadedDuringException()
    {
        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        Audit::setInstance(null);
        // Back to configuaration array (default settings)
        Yii::$app->setModule('audit', ['class' => Audit::className()]);

        $exception = new Exception('Unexpected error triggered before bootstrap');
        Yii::$app->errorHandler->logException($exception);

        $this->assertInstanceOf(Audit::className(), Audit::getInstance());
        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertEquals($oldId + 1, $newId, 'Expected error entry to be created');
    }

    public function testModuleIsNotAutoloadedDuringAMemoryExecption()
    {
        $module = Audit::getInstance();
        $oldId = $this->tester->fetchTheLastModelPk(AuditError::className());

        Audit::setInstance(null);
        // Back to configuaration array (default settings)
        Yii::$app->setModule('audit', ['class' => Audit::className()]);

        $exception = new Exception('Allowed memory size of ...');
        Yii::$app->errorHandler->logException($exception);

        $this->assertNull(Audit::getInstance());

        // Restore module for database
        Audit::setInstance($module);
        $newId = $this->tester->fetchTheLastModelPk(AuditError::className());
        $this->assertEquals($oldId, $newId, 'Expected error entry not to be created');
    }

}