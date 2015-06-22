<?php

use yii\db\Schema;

class m150622_000001_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'request_method', 'varchar(255) NULL AFTER memory_max');
        $this->createIndex('idx_audit_entry_request_method', self::TABLE, ['request_method']);

        $query = \bedezign\yii2\audit\models\AuditEntry::find();
        foreach ($query->batch() as $auditEntries) {
            foreach ($auditEntries as $auditEntry) {
                $auditEntry->request_method = \yii\helpers\ArrayHelper::getValue($auditEntry->data, 'env.REQUEST_METHOD');
                if (!$auditEntry->request_method) {
                    $auditEntry->request_method = 'CLI';
                }
                $auditEntry->save(false, ['request_method']);
            }
        }

    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'request_method');
    }
}
