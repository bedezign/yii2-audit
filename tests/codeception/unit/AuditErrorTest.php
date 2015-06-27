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

    public function testGetEntry()
    {
        $error = AuditError::findOne(1);
        $this->assertEquals($error->getEntry()->one()->className(), AuditEntry::className());
    }

}