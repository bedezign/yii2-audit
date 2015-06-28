<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\tests\UnitTester;
use Yii;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditData;
use Codeception\Specify;

/**
 * AuditUserIdentifierCallbackTest
 */
class AuditUserIdentifierCallbackTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testUserIdentifierCallbackTest()
    {
        Audit::getInstance()->userIdentifierCallback = ['app\models\User', 'userIdentifierCallback'];
        $this->assertEquals(Audit::getInstance()->getUserIdentifier(1), 'admin');
    }

}