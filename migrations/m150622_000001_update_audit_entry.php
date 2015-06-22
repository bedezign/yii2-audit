<?php

use yii\db\Schema;

class m150622_000001_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'request_method', 'varchar(255) NULL AFTER memory_max');
        $this->createIndex('idx_audit_entry_request_method', self::TABLE, ['request_method']);
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'request_method');
    }
}
