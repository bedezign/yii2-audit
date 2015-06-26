<?php
use yii\db\Schema;

class m150625_000001_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $this->dropColumn(self::TABLE, 'url');
        $this->dropColumn(self::TABLE, 'redirect');
        $this->dropColumn(self::TABLE, 'referrer');
        $this->alterColumn(self::TABLE, 'route', Schema::TYPE_STRING . '(512)');
        $this->alterColumn(self::TABLE, 'request_method', Schema::TYPE_STRING . '(16)');
    }

    public function down()
    {
        $this->addColumn(self::TABLE, 'url', Schema::TYPE_STRING . '(255)');
        $this->addColumn(self::TABLE, 'redirect', Schema::TYPE_STRING . '(255)');
        $this->addColumn(self::TABLE, 'referrer', Schema::TYPE_STRING . '(255)');
        $this->alterColumn(self::TABLE, 'route', Schema::TYPE_STRING . '(255)');
        $this->alterColumn(self::TABLE, 'request_method', Schema::TYPE_STRING . '(255)');
    }
}
