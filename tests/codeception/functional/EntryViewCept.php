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

$I->click('Request', '.list-group-item');
$I->see('Request', 'h1');

$I->click('Database', '.list-group-item');
$I->see('Database Queries', 'h1');

$I->click('Logs', '.list-group-item');
$I->see('Log Messages', 'h1');

$I->click('Profiling', '.list-group-item');
$I->see('Performance Profiling', 'h1');

$I->click('Errors', '.list-group-item');
$I->see('Errors', 'h1');

$I->click('Javascripts', '.list-group-item');
$I->see('Javascripts', 'h1');

$I->click('Trails', '.list-group-item');
$I->see('Trails', 'h1');

$I->click('Extra Data', '.list-group-item');
$I->see('Extra Data', 'h1');

//$I->click('Asset Bundles');
//$I->see('asset bundles were loaded');
//
//$I->click('Configuration');
//$I->seeLink('Application Configuration');

$I->click('Mails', '.list-group-item');
$I->see('Mails', 'h1');

$I->click('cURL', '.list-group-item');
$I->see('cURL', 'h1');
$I->see('Starting url');
$I->see('Effective url');
$I->see('Content - JSON');

$I->click('Log');
$I->see('About to connect() to');