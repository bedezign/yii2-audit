<?php

use yii\db\Schema;

class m150626_000005_create_audit_javascript extends \yii\db\Migration
{
    const TABLE = '{{%audit_javascript}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => Schema::TYPE_PK,
            'entry_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'created'    => Schema::TYPE_DATETIME . ' NOT NULL',
            'type'       => Schema::TYPE_STRING . '(20) NOT NULL',
            'message'    => Schema::TYPE_TEXT . ' NOT NULL',
            'origin'     => Schema::TYPE_STRING . '(512)',
            'data'       => 'BLOB NULL',
        ], $this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null);

        $this->addForeignKey('fk_audit_javascript_entry_id', self::TABLE, ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
