<?php

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m170126_000001_alter_audit_mail extends Migration
{
    const TABLE = '{{%audit_mail}}';

    public function up()
    {
        if ($this->db->driverName === 'mysql') {
            $this->alterColumn(self::TABLE, 'data', 'LONGBLOB');
        } else {
            $this->alterColumn(self::TABLE, 'data', 'BYTEA');
        }
    }
}
