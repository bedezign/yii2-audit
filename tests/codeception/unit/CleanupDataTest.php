<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditData;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\models\AuditTrail;
use Codeception\Specify;

/**
 * CleanupDataTest
 */
class CleanupDataTest extends AuditTestCase
{

    public function testCleanupPanels()
    {
        $this->cleanupModelPanel('audit/mail', AuditMail::className());
        $this->cleanupModelPanel('audit/trail', AuditTrail::className());
        $this->cleanupModelPanel('audit/error', AuditError::className());
        $this->cleanupModelPanel('audit/javascript', AuditJavascript::className());
        $this->cleanupDataPanel('audit/curl');
        $this->cleanupDataPanel('audit/db');
        $this->cleanupDataPanel('audit/extra');
        $this->cleanupDataPanel('audit/log');
        $this->cleanupDataPanel('audit/profiling');
        $this->cleanupDataPanel('audit/request');
    }

    private function cleanupModelPanel($panelId, $modelClass)
    {
        $panel = Audit::getInstance()->getPanel($panelId);
        $this->tester->seeRecord($modelClass);
        $panel->cleanup(0);
        $this->tester->dontSeeRecord($modelClass);
    }

    private function cleanupDataPanel($panelId)
    {
        $panel = Audit::getInstance()->getPanel($panelId);
        $this->tester->seeRecord(AuditData::className(), ['type' => $panelId]);
        $panel->cleanup(0);
        $this->tester->dontSeeRecord(AuditData::className(), ['type' => $panelId]);
    }

}