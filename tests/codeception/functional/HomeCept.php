<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that home page works');
$I->amOnPage(Url::to(['/audit']));
$I->see('Audit');

$I->seeLink('Entries');
$I->click('Entries');
$I->see('Entries', 'h1');
$I->click('Audit');

$I->seeLink('Trails');
$I->click('Trails');
$I->see('Trails', 'h1');
$I->click('Audit');

$I->seeLink('Mails');
$I->click('Mails');
$I->see('Mails', 'h1');
$I->click('Audit');

$I->seeLink('Javascripts');
$I->click('Javascripts');
$I->see('Javascripts', 'h1');
$I->click('Audit');

$I->seeLink('Errors');
$I->click('Errors');
$I->see('Errors', 'h1');
$I->click('Audit');