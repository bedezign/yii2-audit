<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditErrorSearch;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditJavascriptSearch;
use Codeception\Specify;

/**
 * AuditErrorSearchTest
 */
class AuditErrorSearchTest extends AuditTestCase
{

    public function testFileFilterWorks()
    {
        $this->assertEquals(['/vagrant/git/yii2-audit/src/views/entry/view.php' => '/vagrant/git/yii2-audit/src/views/entry/view.php'], AuditErrorSearch::fileFilter());
    }

}