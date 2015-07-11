<?php
use yii\db\Schema;

class m150622_000003_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';
    public function up()
    {
        $this->dropColumn(self::TABLE, 'origin');
    }
    public function down()
    {
        $this->addColumn(self::TABLE, 'origin', 'varchar(512) NULL AFTER ip');
    }
}
