<?php

namespace bedezign\yii2\audit\migrations;

use yii\db\Migration;

/**
 * Class m231117_101719_alter_audit_trail
 */
class m231117_101719_alter_audit_trail extends Migration
{
    const TABLE = '{{%audit_trail}}';

    public function up()
    {
        $this->alterColumn(self::TABLE, 'user_id', $this->string(255)->null());
    }
}
