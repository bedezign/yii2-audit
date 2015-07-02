<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditEntry;
use Codeception\Specify;

/**
 * AuditErrorTest
 */
class AuditEntryTest extends AuditTestCase
{
    public function testEnabledBatchSaveOptionIsRespected()
    {
        $module = $this->module();
        $module->batchSave = true;

        $mock = $this->getMock(AuditEntry::className(), ['addBatchData', 'addData']);
        $mock->expects($this->once())->method('addBatchData');
        $mock->expects($this->never())->method('addData');
        $this->useEntry($mock);

        $this->finalizeAudit(false);
    }

    public function testBatchSaveOptionIsRespected()
    {
        $module = $this->module();
        $module->batchSave = false;

        $mock = $this->getMock(AuditEntry::className(), ['addBatchData', 'addData']);
        $mock->expects($this->never())->method('addBatchData');
        $mock->expects($this->atLeastOnce())->method('addData');
        $this->useEntry($mock);

        $this->finalizeAudit(false);
    }

}