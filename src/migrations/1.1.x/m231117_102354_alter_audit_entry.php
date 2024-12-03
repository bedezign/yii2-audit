<?php

use bedezign\yii2\audit\components\Migration;

/**
 * Class m231117_102354_alter_audit_entry
 */
class m231117_102354_alter_audit_entry extends Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $this->alterColumn(self::TABLE, 'user_id', $this->string(255)->null()->defaultValue(0));
    }
}
