<?php

namespace bedezign\yii2\audit\migrations;

use bedezign\yii2\audit\components\Migration;

class m250929_073712_increase_content_length extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->alterColumn('{{%audit_trail}}', 'old_value', $this->longText()->null());
        $this->alterColumn('{{%audit_trail}}', 'new_value', $this->longText()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->alterColumn('{{%audit_trail}}', 'old_value', $this->text()->null());
        $this->alterColumn('{{%audit_trail}}', 'new_value', $this->text()->null());
    }

    protected function longText()
    {
        switch ($this->getDb()->getDriverName()) {
            case 'mysql':
                // MySQL supports LONGTEXT (4GB max)
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('LONGTEXT');
            case 'pgsql':
                // PostgreSQL TEXT type already supports unlimited length
                return $this->text();
            case 'mssql':
            case 'dblib':
                // SQL Server uses VARCHAR(MAX)
                return $this->getDb()->getSchema()->createColumnSchemaBuilder('VARCHAR(MAX)');
            default:
                // Fallback to regular text
                return $this->text();
        }
    }
}
