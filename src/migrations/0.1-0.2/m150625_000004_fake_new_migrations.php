<?php

use yii\db\Schema;

class m150625_000004_fake_new_migrations extends \yii\db\Migration
{
    public function up()
    {
        $now = time();
        $this->batchInsert(\Yii::$app->controller->migrationTable, ['version', 'apply_time'], [
            ['m150626_000001_create_audit_entry', $now],
            ['m150626_000002_create_audit_data', $now],
            ['m150626_000003_create_audit_error', $now],
            ['m150626_000004_create_audit_trail', $now],
            ['m150626_000005_create_audit_javascript', $now],
        ]);
    }

}
