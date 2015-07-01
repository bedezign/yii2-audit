<?php

use bedezign\yii2\audit\tests\FunctionalTester;
use Codeception\Scenario;
use yii\helpers\Url;

/**
 * @var $scenario Scenario
 */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that mail download works');
$I->amOnPage(Url::to(['/audit/mail/download', 'id' => 1]));
$I->canSeeResponseCodeIs(200);

$I->wantTo('ensure that mail download gives error with bad id');
$I->amOnPage(Url::to(['/audit/mail/download', 'id' => 99999]));
$I->see('Not Found (#404)', 'h1');

