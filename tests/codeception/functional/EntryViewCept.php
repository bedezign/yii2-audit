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

$I->amOnPage(Url::to(['/audit/entry/view', 'id' => 1]));
$I->see('Entry #1', 'h1');

$icon = '<i class="glyphicon glyphicon-chevron-right"></i> ';
$I->click($icon . 'Request');
$I->see('Routing');

$I->click($icon . 'Database');
$I->see('Database Queries');

$I->click($icon . 'Logs');
$I->seeLink('Log Messages');

$I->click($icon . 'Asset Bundles');
$I->see('asset bundles were loaded');

$I->click($icon . 'Configuration');
$I->seeLink('Application Configuration');

$I->click($icon . 'Mail');
$I->seeLink('Email Messages');

$I->click($icon . 'Performance');
$I->see('Performance Profiling');