<?php

use yii\db\Schema;

class m150625_000003_update_audit_trail extends \yii\db\Migration
{
    const TABLE = '{{%audit_trail}}';

    public function up()
    {
        $this->renameColumn(self::TABLE, 'stamp', 'created');
    }

    public function down()
    {
        $this->renameColumn(self::TABLE, 'created', 'stamp');
    }
}
