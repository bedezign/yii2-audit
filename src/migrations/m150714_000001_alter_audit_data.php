<?php

namespace bedezign\yii2\audit\migrations;

use bedezign\yii2\audit\components\Migration;
use yii\db\Schema;

class m150714_000001_alter_audit_data extends Migration
{
    const TABLE = '{{%audit_data}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'created', Schema::TYPE_DATETIME);
    }

}
