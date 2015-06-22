<?php
use yii\db\Schema;
class m150622_000002_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';
    public function up()
    {
        $this->addColumn(self::TABLE, 'redirect', 'varchar(255) NULL AFTER origin');
    }
    public function down()
    {
        $this->dropColumn(self::TABLE, 'redirect');
    }
}
