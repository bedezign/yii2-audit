<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that mail view works');
$I->amOnPage(Url::to(['/audit/mail/view', 'id' => 1]));
$I->see('Mail #1', 'h1');

$I->wantTo('ensure that mail view gives error with bad id');
$I->amOnPage(Url::to(['/audit/mail/view', 'id' => 99999]));
$I->see('Not Found (#404)', 'h1');


//TODO

//$I->click('Download eml');
//$I->canSeeResponseCodeIs(200);
//
//$I->amOnPage(Url::to(['/audit/entry/download-mail', 'file' => 'invalid-mail-file.eml']));
//$I->see('Not Found (#404)', 'h1');