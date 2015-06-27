<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use bedezign\yii2\audit\models\AuditErrorSearch;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditJavascriptSearch;
use Codeception\Specify;

/**
 * AuditErrorSearchTest
 */
class AuditEntrySearchTest extends AuditTestCase
{

    public function testRouteFilterWorks()
    {
        $this->assertEquals(['/default/index' => '/default/index'], AuditEntrySearch::routeFilter());
    }

    public function testMethodFilterWorks()
    {
        $this->assertEquals(['GET' => 'GET'], AuditEntrySearch::methodFilter());
    }

}