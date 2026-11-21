<?php
// file generated with AI assistance: Claude Code - 2026-11-21 22:06:00 UTC

namespace tests\codeception\unit;

use Yii;
use yii\log\Logger;

/**
 * DbPanelTest
 */
class DbPanelTest extends AuditTestCase
{
    protected function messages()
    {
        return [
            ['SELECT * FROM `audit_entry`', Logger::LEVEL_PROFILE_BEGIN, 'yii\db\Command::query', 1435385301.7008, []],
            ['SELECT * FROM `audit_entry`', Logger::LEVEL_PROFILE_END, 'yii\db\Command::query', 1435385301.7016, []],
            ['DELETE FROM `audit_entry`', Logger::LEVEL_PROFILE_BEGIN, 'yii\db\Command::execute', 1435385301.7045, []],
            ['DELETE FROM `audit_entry`', Logger::LEVEL_PROFILE_END, 'yii\db\Command::execute', 1435385301.7048, []],
        ];
    }

    protected function panel($data)
    {
        $panel = $this->module()->getPanel('audit/db');
        $panel->data = $data;
        return $panel;
    }

    public function testTimingsContainTraceHash()
    {
        $timings = $this->panel(['messages' => $this->messages()])->calculateTimings();

        $this->assertCount(2, $timings);
        foreach ($timings as $timing) {
            $this->assertArrayHasKey('traceHash', $timing);
        }
    }

    public function testNoTimingsWithoutStoredMessages()
    {
        $panel = $this->panel([]);

        $this->assertSame([], $panel->calculateTimings());
        $this->assertContains('(0 / 0 ms)', $panel->getLabel());
    }

    public function testDetailRendersQueriesAndCallers()
    {
        // The views build sort and explain links from the active controller, as in the entry view
        Yii::$app->controller = $this->module()->createController('entry')[0];
        try {
            $detail = $this->panel(['messages' => $this->messages()])->getDetail();
        } finally {
            Yii::$app->controller = null;
        }

        $this->assertContains('SELECT * FROM `audit_entry`', $detail);
        $this->assertContains('Callers', $detail);
    }
}
