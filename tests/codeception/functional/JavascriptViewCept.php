<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that javascript view works');

$I->amOnPage(Url::to(['/audit/javascript/view', 'id' => 1]));
$I->see('JS #1', 'h1');
