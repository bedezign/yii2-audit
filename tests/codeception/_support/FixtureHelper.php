<?php

namespace tests\codeception\_support;

use Codeception\Module;
use Codeception\TestCase;
use tests\codeception\_fixtures\AuditDataFixture;
use tests\codeception\_fixtures\AuditEntryFixture;
use tests\codeception\_fixtures\AuditErrorFixture;
use tests\codeception\_fixtures\AuditJavascriptFixture;
use tests\codeception\_fixtures\AuditTrailFixture;
use tests\codeception\_fixtures\PostFixture;
use tests\codeception\_fixtures\UserFixture;
use yii\test\FixtureTrait;

class FixtureHelper extends Module
{
    use FixtureTrait;

    /**
     * @var array
     */
    public static $excludeActions = ['loadFixtures', 'unloadFixtures', 'getFixtures', 'globalFixtures', 'fixtures'];

    /**
     * @param TestCase $testcase
     */
    public function _before(TestCase $testcase)
    {
        $this->unloadFixtures();
        $this->loadFixtures();
        parent::_before($testcase);
    }

    /**
     * @param TestCase $testcase
     */
    public function _after(TestCase $testcase)
    {
        $this->unloadFixtures();
        parent::_after($testcase);
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            'audit_entry' => [
                'class' => AuditEntryFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_audit_entry.php',
            ],
            'audit_data' => [
                'class' => AuditDataFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_audit_data.php',
            ],
            'audit_error' => [
                'class' => AuditErrorFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_audit_error.php',
            ],
            'audit_javascript' => [
                'class' => AuditJavascriptFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_audit_javascript.php',
            ],
            'audit_trail' => [
                'class' => AuditTrailFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_audit_trail.php',
            ],
            'post' => [
                'class' => PostFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_post.php',
            ],
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => '@tests/codeception/_fixtures/data/init_user.php',
            ],
        ];
    }
}
