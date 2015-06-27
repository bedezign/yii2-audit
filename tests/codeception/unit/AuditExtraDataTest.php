<?php

namespace tests\codeception\unit;

use app\models\Post;
use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditTrail;
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
        Audit::getInstance()->data('some type', 'some data');
        $this->finalizeAudit();
        
        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => 2,
            'type' => 'audit/extra',
        ]);

    }
}