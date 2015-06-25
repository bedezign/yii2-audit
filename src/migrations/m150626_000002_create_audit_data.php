<?php

use yii\db\Schema;

class m150626_000002_create_audit_data extends \yii\db\Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => Schema::TYPE_PK,
            'entry_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'type'       => Schema::TYPE_STRING . '(255) NOT NULL',
            'data'       => 'BLOB NOT NULL',
        ], ($this->db->driverName === 'mysql' ? 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB' : null));

        $this->addForeignKey('fk_audit_data_entry_id', self::TABLE, ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
