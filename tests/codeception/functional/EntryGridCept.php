<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that entry grid works');
$I->amOnPage(Url::to(['/audit/entry', 'AuditEntrySearch' => ['id' => 1]]));
$I->see('Entries', 'h1');

