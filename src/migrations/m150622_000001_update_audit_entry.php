<?php

use yii\db\Schema;

class m150622_000001_update_audit_entry extends \yii\db\Migration
{
    const TABLE = '{{%audit_entry}}';

    public function up()
    {
        $this->addColumn(self::TABLE, 'request_method', 'varchar(255) NULL AFTER memory_max');
        $this->createIndex('idx_audit_entry_request_method', self::TABLE, ['request_method']);

        echo "    > filling 'request_method' from data ...";
        $time = microtime(true);
        foreach (\bedezign\yii2\audit\models\AuditEntry::find()->where(['request_method' => null])->batch() as $auditEntries) {
            foreach ($auditEntries as $auditEntry) {
                $auditEntry->request_method = \yii\helpers\ArrayHelper::getValue($auditEntry->data, 'env.REQUEST_METHOD');
                if (!$auditEntry->request_method) {
                    $auditEntry->request_method = 'CLI';
                }
                $auditEntry->save(false, ['request_method']);
            }
        }
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }

    public function down()
    {
        $this->dropColumn(self::TABLE, 'request_method');
    }
}
