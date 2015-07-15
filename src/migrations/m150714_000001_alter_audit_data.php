<?php

use yii\db\Migration;
use yii\db\Schema;

class m150714_000001_alter_audit_data extends Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        if ($this->db->driverName != 'sqlite') {
            $this->addColumn(self::TABLE, 'created', Schema::TYPE_DATETIME . ' NOT NULL DEFAULT NOW()');
            return;
        }
        // adding NOT NULL column to sqlite caused it to bork out
        $this->addColumn(self::TABLE, 'created', Schema::TYPE_DATETIME . ' NOT NULL DEFAULT NOW');
    }

}
