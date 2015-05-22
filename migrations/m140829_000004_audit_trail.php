<?php

use yii\db\Schema;
use yii\db\Migration;

class m140829_000004_audit_trail extends Migration
{
    const TABLE = '{{%audit_trail}}';

    public function up()
    {
        // Create our first version of the audittrail table
        // Please note that this matches the original creation of the
        // table from version 1 of the audittrail extension. Other migrations will
        // upgrade it from here if we ever need to. This was done so
        // that older versions can still use migrate functionality to upgrade.
        $this->createTable(self::TABLE,
            [
                'id'        => Schema::TYPE_PK,
                'audit_id'  => Schema::TYPE_INTEGER,
                'old_value' => Schema::TYPE_TEXT,
                'new_value' => Schema::TYPE_TEXT,
                'action'    => Schema::TYPE_STRING . ' NOT NULL',
                'model'     => Schema::TYPE_STRING . ' NOT NULL',
                'field'     => Schema::TYPE_STRING,
                'stamp'     => Schema::TYPE_DATETIME . ' NOT NULL',
                'model_id'  => Schema::TYPE_STRING . ' NOT NULL',
            ]
        );

        // Index these bad boys for speedy lookups
        $this->createIndex('idx_audit_trail_audit_id', self::TABLE, 'audit_id');
        $this->createIndex('idx_audit_trail_model_id', self::TABLE, 'model_id');
        $this->createIndex('idx_audit_trail_model', self::TABLE, 'model');
        $this->createIndex('idx_audit_trail_field', self::TABLE, 'field');
        $this->createIndex('idx_audit_trail_action', self::TABLE, 'action');
        /* http://stackoverflow.com/a/1827099/383478
         $this->createIndex( 'idx_audit_trail_old_value', '{{%audit_trail}}', 'old_value');
        $this->createIndex( 'idx_audit_trail_new_value', '{{%audit_trail}}', 'new_value');
        */
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->up();
    }

    public function safeDown()
    {
        $this->down();
    }
}
