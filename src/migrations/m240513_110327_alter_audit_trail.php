<?php

namespace bedezign\yii2\audit\migrations;

use yii\db\Migration;

/**
 * Class m240513_110327_alter_audit_trail
 */
class m240513_110327_alter_audit_trail extends Migration
{
    const TABLE = '{{%audit_trail}}';

    public function up()
    {
        $this->alterColumn(self::TABLE, 'old_value', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->null());
        $this->alterColumn(self::TABLE, 'new_value', $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT')->null());
    }
}
