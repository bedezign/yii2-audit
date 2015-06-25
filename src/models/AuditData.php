<?php

namespace bedezign\yii2\audit\models;

/**
 * AuditData
 * Extra custom data associated with a specific audit line. There are currently no guidelines concerning what the name/type
 * needs to be, this is at your own discretion.
 *
 * @property int    $id
 * @property int    $entry_id
 * @property string $type
 * @property string $data
 *
 * @package bedezign\yii2\audit\models
 */
class AuditData extends AuditModel
{
    // By default we do not serialise values (unless they are complex)
    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @param AuditEntry $entry
     */
    public function setEntry(AuditEntry $entry)
    {
        $this->entry_id = $entry->id;
    }

    /**
     * @return AuditEntry
     */
    public function getEntry()
    {
        return static::hasOne(AuditEntry::className(), ['id' => 'entry_id']);
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

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // Only serialize complex values
        if (is_array($this->data) || is_object($this->data)) {
            $this->autoSerialize = true;
            $this->data = \bedezign\yii2\audit\components\Helper::compact($this->data);
        }
        return parent::beforeSave($insert);
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->autoSerialize = $this->packed;
        parent::afterFind();
    }

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%audit_data}}';
    }
}