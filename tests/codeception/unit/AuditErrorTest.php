<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use Codeception\Specify;

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

}