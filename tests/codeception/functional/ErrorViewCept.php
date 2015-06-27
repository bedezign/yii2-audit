<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that error view works');

$I->amOnPage(Url::to(['/audit/error/view', 'id' => 1]));
$I->see('Error #1', 'h1');
$I->see('Stack Trace', 'h2');
