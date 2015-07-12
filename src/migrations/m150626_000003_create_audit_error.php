<?php

use yii\db\Schema;

class m150626_000003_create_audit_error extends \yii\db\Migration
{
    const TABLE = '{{%audit_error}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => Schema::TYPE_PK,
            'entry_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'created'    => Schema::TYPE_DATETIME . ' NOT NULL',
            'message'    => Schema::TYPE_TEXT . ' NOT NULL',
            'code'       => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'file'       => Schema::TYPE_STRING . '(512)',
            'line'       => Schema::TYPE_INTEGER ,
            'trace'      => Schema::TYPE_BINARY,
            'hash'       => Schema::TYPE_STRING . '(32)',
            'emailed'    => Schema::TYPE_BOOLEAN . " NOT NULL DEFAULT '0'",
        ], ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null));

        $this->addForeignKey('fk_audit_error_entry_id', self::TABLE, ['entry_id'], '{{%audit_entry}}', 'id');
        $this->createIndex('idx_message', self::TABLE, ['message(256)']);
        $this->createIndex('idx_file', self::TABLE, ['file']);
        $this->createIndex('idx_emailed', self::TABLE, ['emailed']);
    }

    public function down()
    {
        $this->dropForeignKey('fk_audit_error_entry_id', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
