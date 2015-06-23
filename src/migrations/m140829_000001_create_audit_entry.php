<?php

use yii\db\Schema;

class m140829_000001_create_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE, [
            'id'         => Schema::TYPE_PK,
            'created'    => Schema::TYPE_DATETIME . ' NOT NULL',
            'start_time' => Schema::TYPE_FLOAT . ' NULL',
            'end_time'   => Schema::TYPE_FLOAT . ' NULL',
            'duration'   => Schema::TYPE_FLOAT . ' NULL',
            'user_id'    => Schema::TYPE_INTEGER . " DEFAULT '0'",
            'ip'         => Schema::TYPE_STRING . '(45) NULL',
            'referrer'   => Schema::TYPE_STRING . '(512) NULL',
            'origin'     => Schema::TYPE_STRING . '(512) NULL',
            'url'        => Schema::TYPE_STRING . '(512) NULL',
            'route'      => Schema::TYPE_STRING . '(255) NULL',
            'data'       => 'BLOB NULL',
            'memory'     => Schema::TYPE_INTEGER . " NULL",
            'memory_max' => Schema::TYPE_INTEGER . " NULL",

        ], $tableOptions);

        $this->createIndex('idx_user_id', self::TABLE, ['user_id']);
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
