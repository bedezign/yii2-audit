<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that entry view and panels works');

$I->amOnPage(Url::to(['/audit/entry/view', 'id' => 1]));
$I->see('Entry #1', 'h1');

$I->click('Request');
$I->see('Routing');

$I->click('Database');
$I->see('Database Queries');

$I->click('Logs');
$I->see('Log Messages');

$I->click('Profiling');
$I->see('Performance Profiling');

$I->click('Error');
$I->see('Error Code');

$I->click('Javascript');
$I->see('Origin');

$I->click('Database Trails');
$I->see('Database Trails');

$I->click('Extra Data');
$I->see('Extra Data');

//$I->click('Asset Bundles');
//$I->see('asset bundles were loaded');
//
//$I->click('Configuration');
//$I->seeLink('Application Configuration');

$I->click('Email Messages');
$I->see('Email Messages');


$I->wantTo('ensure that entry view gives error with bad id');
$I->amOnPage(Url::to(['/audit/entry/view', 'id' => 99999]));
$I->see('Not Found (#404)', 'h1');

