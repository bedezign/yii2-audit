<?php

use yii\db\Schema;
use yii\db\Migration;

class m150624_000002_post extends Migration
{
    public function up()
    {
        $this->createTable('post', [
            'id' => Schema::TYPE_PK,
            'title' => Schema::TYPE_STRING,
            'body' => Schema::TYPE_TEXT,
        ]);
    }

    public function down()
    {
        $this->dropTable('post');
    }

}
