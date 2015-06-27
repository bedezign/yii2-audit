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
$I->wantTo('ensure that entry mail panel works');

$I->amOnPage(Url::to(['/audit/entry/view', 'id' => 1, 'panel' => 'audit/mail']));
$I->see('Email Messages');
$I->click('Download eml');
$I->canSeeResponseCodeIs(200);

$I->amOnPage(Url::to(['/audit/entry/download-mail', 'file' => 'invalid-mail-file.eml']));
$I->see('Not Found (#404)', 'h1');