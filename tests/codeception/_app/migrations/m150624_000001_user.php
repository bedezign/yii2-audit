<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_000001_user extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING,
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }

}
