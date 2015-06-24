<?php

class m150623_000003_add_audit_entry_ajax extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'ajax', 'int(11) NOT NULL AFTER request_method');
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'ajax');
    }
}
