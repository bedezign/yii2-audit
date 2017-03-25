<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\tests\UnitTester;

/**
 * AuditExtraDataTest
 */
class AuditExtraDataTest extends AuditTestCase
{

    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * Add Data
     */
    public function testAddData()
    {
        $this->entry();
        $this->finalizeAudit();

        $this->entry();
        $this->module()->data('some type', 'some data');
        $this->finalizeAudit();

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => 3,
            'type' => 'audit/extra',
        ]);
    }

    public function testThatFindForEntryWorks()
    {
        $this->entry();
        $this->finalizeAudit();

        $this->entry();
        $this->finalizeAudit();

        $this->assertNotNull(AuditData::findForEntry(3, 'audit/request'));
        $this->assertNotNull(AuditData::findForEntry(3, 'audit/profiling'));
    }

    public function testThatFindEntryTypesWorks()
    {
        $this->entry();
        $this->finalizeAudit();

        $this->entry();
        $this->finalizeAudit();

        $types = AuditData::findEntryTypes(3);
        $subset = [
            'audit/config',
            //'audit/db',
            //'audit/log',
            'audit/profiling',
            'audit/request',
        ];
        sort($types);
        sort($subset);
        $this->assertArraySubset($subset, $types);
    }

}