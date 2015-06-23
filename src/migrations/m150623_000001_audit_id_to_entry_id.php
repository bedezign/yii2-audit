<?php

use bedezign\yii2\audit\models\AuditError;

class m150623_000001_audit_id_to_entry_id extends \yii\db\Migration
{
    const TABLE = '{{%audit_error}}';

    public function safeUp()
    {
        $this->dropForeignKey('fk_audit_data_audit_id', 'audit_data');
        $this->dropForeignKey('fk_audit_error_audit_id', 'audit_error');
        $this->dropForeignKey('fk_audit_javascript_audit_id', 'audit_javascript');
        //$this->dropForeignKey('fk_audit_trail_audit_id', 'audit_trail'); //no FK

        $this->dropIndex('fk_audit_data_audit_id', 'audit_data');
        $this->dropIndex('fk_audit_error_audit_id', 'audit_error');
        $this->dropIndex('fk_audit_javascript_audit_id', 'audit_javascript');
        $this->dropIndex('idx_audit_trail_audit_id', 'audit_trail'); // named idx_

        $this->renameColumn('audit_data', 'audit_id', 'entry_id');
        $this->renameColumn('audit_error', 'audit_id', 'entry_id');
        $this->renameColumn('audit_javascript', 'audit_id', 'entry_id');
        $this->renameColumn('audit_trail', 'audit_id', 'entry_id');

        $this->addForeignKey('fk_audit_data_entry_id', 'audit_data', ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_audit_error_entry_id', 'audit_error', ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_audit_javascript_entry_id', 'audit_javascript', ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_audit_trail_entry_id', 'audit_trail', ['entry_id'], '{{%audit_entry}}', 'id', 'CASCADE');

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_audit_data_entry_id', 'audit_data');
        $this->dropForeignKey('fk_audit_error_entry_id', 'audit_error');
        $this->dropForeignKey('fk_audit_javascript_entry_id', 'audit_javascript');
        $this->dropForeignKey('fk_audit_trail_entry_id', 'audit_trail');

        $this->dropIndex('fk_audit_data_entry_id', 'audit_data');
        $this->dropIndex('fk_audit_error_entry_id', 'audit_error');
        $this->dropIndex('fk_audit_javascript_entry_id', 'audit_javascript');
        $this->dropIndex('fk_audit_trail_entry_id', 'audit_trail');

        $this->renameColumn('audit_data', 'entry_id', 'audit_id');
        $this->renameColumn('audit_error', 'entry_id', 'audit_id');
        $this->renameColumn('audit_javascript', 'entry_id', 'audit_id');
        $this->renameColumn('audit_trail', 'entry_id', 'audit_id');

        $this->addForeignKey('fk_audit_data_audit_id', 'audit_data', ['audit_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_audit_error_audit_id', 'audit_error', ['audit_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->addForeignKey('fk_audit_javascript_audit_id', 'audit_javascript', ['audit_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        //$this->addForeignKey('fk_audit_trail_audit_id', 'audit_trail', ['audit_id'], '{{%audit_entry}}', 'id', 'CASCADE');
        $this->createIndex('idx_audit_trail_audit_id', 'audit_trail', ['audit_id']);
    }
}
