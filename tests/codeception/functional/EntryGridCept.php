<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that entries grid and view works');
$I->amOnPage(Url::to(['/audit/entry']));
$I->see('Entries', 'h1');
