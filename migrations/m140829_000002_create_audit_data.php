<?php

use yii\db\Schema;

class m140829_000002_create_audit_data extends \yii\db\Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE, [
            'id'         => Schema::TYPE_PK,
            'audit_id'   => Schema::TYPE_INTEGER . ' NOT NULL',
            'name'       => Schema::TYPE_STRING . '(255) NOT NULL',
            'type'       => Schema::TYPE_STRING . '(255) NULL',
            'packed'     => "TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'",
            'data'       => 'BLOB NOT NULL',
        ], $tableOptions);

        $this->addForeignKey('fk_audit_data_audit_id', self::TABLE, ['audit_id'], '{{%audit_entry}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
