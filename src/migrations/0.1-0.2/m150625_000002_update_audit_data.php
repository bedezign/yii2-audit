<?php
use yii\db\Schema;

class m150625_000002_update_audit_data extends \yii\db\Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        $this->dropColumn(self::TABLE, 'name');
        $this->dropColumn(self::TABLE, 'packed');
    }

    public function down()
    {
        $this->addColumn(self::TABLE, 'name', 'VARCHAR(255) NULL AFTER entry_id');
        $this->addColumn(self::TABLE, 'packed', 'TINYINT(1) UNSIGNED DEFAULT 0 AFTER type');
    }
}
