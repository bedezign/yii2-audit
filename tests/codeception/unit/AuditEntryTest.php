<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\models\AuditEntry;
use Codeception\Specify;

/**
 * AuditEntryTest
 */
class AuditEntryTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testEnabledBatchSaveOptionIsRespected()
    {
        $module = $this->module();
        $module->batchSave = true;

        $mock = $this->getMock(AuditEntry::className());
        $mock->expects($this->once())->method('addBatchData');
        $mock->expects($this->never())->method('addData');
        $this->useEntry($mock);

        $this->finalizeAudit(false);
    }

    public function testBatchSaveOptionIsRespected()
    {
        $module = $this->module();
        $module->batchSave = false;

        $mock = $this->getMock(AuditEntry::className());
        $mock->expects($this->never())->method('addBatchData');
        $mock->expects($this->atLeastOnce())->method('addData');
        $this->useEntry($mock);

        $this->finalizeAudit(false);
    }

    public function testThatAddDataWorks()
    {
        $oldId = $this->tester->fetchTheLastModelPk(AuditData::className());
        $module = $this->module();
        $module->batchSave = false;
        $entry = $this->entry();

        $this->finalizeAudit();

        $newId = $this->tester->fetchTheLastModelPk(AuditData::className());
        $this->assertGreaterThan($oldId, $newId);
        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entry->id,
            'type' => 'audit/request',
        ]);
    }

    public function testBatchSaveOptionIsWorksUncompressed()
    {
        $module = $this->module();
        $module->batchSave = false;
        $module->compressData = false;

        $entry = $this->entry();
        $this->finalizeAudit();

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entry->id,
            'type' => 'audit/request',
        ]);
    }

    public function testThatAddDataWorksUncompressed()
    {
        $module = $this->module();
        $module->compressData = false;

        $entry = $this->entry();
        $this->finalizeAudit();

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entry->id,
            'type' => 'audit/request',
        ]);
    }
}