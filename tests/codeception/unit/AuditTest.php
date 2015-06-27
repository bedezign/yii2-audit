<?php

namespace tests\codeception\unit;

use Yii;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditData;
use Codeception\Specify;

/**
 * AuditTest
 */
class AuditTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testThatEntryIsCreated()
    {
        $oldEntryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());
        $this->module()->getEntry(true);
        $newEntryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());
        $this->assertNotEquals($oldEntryId, $newEntryId, 'I expected that a new entry was added');
    }

    public function testThatCustomDataCanBeAttachedToAnEntry()
    {
        $this->module()->getEntry(true);
        $entryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());

        $originalData = ['some', 'array', 'with', 'test', 'data'];

        $oldDataId = $this->tester->fetchTheLastModelPk(AuditData::className());
        $this->module()->data('custom-test', $originalData);
        $this->finalizeAudit();

        $newDataId = $this->tester->fetchTheLastModelPk(AuditData::className());
        $this->assertNotEquals($oldDataId, $newDataId, 'I expected that a new data entry was added');

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entryId,
            'type' => 'audit/custom',
        ]);

        //$data = AuditData::findOne($newDataId);
        //$this->assertEquals($)
    }
}