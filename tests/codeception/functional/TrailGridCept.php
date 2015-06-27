<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that trail grid works');
$I->amOnPage(Url::to(['/audit/trail']));
$I->see('Trails', 'h1');
