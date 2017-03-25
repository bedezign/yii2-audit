<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\tests\UnitTester;
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
        $this->module()->batchSave = true;

        $mock = $this->getMockBuilder(AuditEntry::className())->getMock();
        $mock->expects($this->once())->method('addBatchData');
        $mock->expects($this->never())->method('addData');

        $this->finalizeAudit();
        $this->useEntry($mock);
        $this->finalizeAudit(false);
    }

    public function testBatchSaveOptionIsRespected()
    {
        $this->module()->batchSave = false;

        $mock = $this->getMockBuilder(AuditEntry::className())->getMock();
        $mock->expects($this->never())->method('addBatchData');
        $mock->expects($this->atLeastOnce())->method('addData');

        $this->finalizeAudit();
        $this->module()->batchSave = false;
        $this->useEntry($mock);
        $this->finalizeAudit(false);
    }

    public function testThatAddDataWorks()
    {
        $this->module()->batchSave = false;
        $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = false;

        $oldEntry = $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = false;
        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $oldEntry->id,
            'type' => 'audit/request',
        ]);

        $newEntry = $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = false;
        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $newEntry->id,
            'type' => 'audit/request',
        ]);

        $this->assertGreaterThan($oldEntry->id, $newEntry->id);
    }

    public function testBatchSaveOptionIsWorksUncompressed()
    {
        $this->module()->batchSave = true;
        $this->module()->compressData = false;
        $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = true;
        $this->module()->compressData = false;

        $entry = $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = true;
        $this->module()->compressData = false;

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entry->id,
            'type' => 'audit/request',
        ]);
    }

    public function testThatAddDataWorksUncompressed()
    {
        $this->module()->batchSave = false;
        $this->module()->compressData = false;
        $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = false;
        $this->module()->compressData = false;

        $entry = $this->entry();
        $this->finalizeAudit();
        $this->module()->batchSave = false;
        $this->module()->compressData = false;

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entry->id,
            'type' => 'audit/request',
        ]);
    }
}