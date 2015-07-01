<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);

$I->wantTo('ensure that trail view works');
$I->amOnPage(Url::to(['/audit/trail/view', 'id' => 1]));
$I->see('Trail #1', 'h1');
$I->see('Difference', 'h2');

$I->wantTo('ensure that trail view gives error with bad id');
$I->amOnPage(Url::to(['/audit/trail/view', 'id' => 99999]));
$I->see('Not Found (#404)', 'h1');