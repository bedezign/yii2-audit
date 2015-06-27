<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

$src = __DIR__ . '/../_fixtures/data/mail/20150627-101704-5530-2364.eml';
$dest = __DIR__ . '/../_app/runtime/debug/mail/20150627-101704-5530-2364.eml';
if (!is_dir(dirname($dest)))
    mkdir(dirname($dest), 0755, true);
copy($src, $dest);

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

$I->click('Trail');
$I->see('Database Trails');

$I->click('Extra');
$I->see('Extra Data');

//$I->click('Asset Bundles');
//$I->see('asset bundles were loaded');
//
//$I->click('Configuration');
//$I->seeLink('Application Configuration');

$I->click('Mail');
$I->see('Email Messages');


