<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);

$I->wantTo('ensure that javascript grid works');
$I->amOnPage(Url::to(['/audit/javascript']));
$I->see('Javascripts', 'h1');

