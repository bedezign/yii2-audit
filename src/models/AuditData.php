<?php

namespace bedezign\yii2\audit\models;
use bedezign\yii2\audit\components\db\ActiveRecord;

/**
 * AuditData
 * Extra data associated with a specific audit line. There are currently no guidelines concerning what the name/type
 * needs to be, this is at your own discretion.
 *
 * @property int    $id
 * @property int    $entry_id
 * @property string $type
 * @property string $data
 *
 * @property AuditEntry    $entry
 *
 * @package bedezign\yii2\audit\models
 */
class AuditData extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_data}}';
    }

    /**
     * @param $entry_id
     * @return array
     */
    public static function findEntryTypes($entry_id)
    {
        return static::find()->select('type')->where(['entry_id' => $entry_id])->column();
    }

    /**
     * @param $entry_id
     * @param $type
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findForEntry($entry_id, $type)
    {
        return static::find()->where(['entry_id' => $entry_id, 'type' => $type])->one();
    }

}