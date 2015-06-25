<?php

use yii\db\Schema;

class m150609_000001_update_audit_trail extends \yii\db\Migration
{
    const TABLE = '{{%audit_trail}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'user_id', 'int(11) NULL AFTER audit_id');
        $this->createIndex('idx_audit_entry_user_id', self::TABLE, ['user_id']);
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'user_id');
    }
}
