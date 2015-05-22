<?php

namespace bedezign\yii2\audit\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * The followings are the available columns in table 'tbl_audit_trail':
 *
 * @var integer $id
 * @var string  $new_value
 * @var string  $old_value
 * @var string  $action
 * @var string  $model
 * @var string  $field
 * @var string  $stamp
 * @var integer $user_id
 * @var string  $model_id
 */
class AuditTrail extends AuditModel
{
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('audit', 'ID'),
            'audit_id'  => Yii::t('audit', 'Entry ID'),
            'old_value' => Yii::t('audit', 'Old Value'),
            'new_value' => Yii::t('audit', 'New Value'),
            'action'    => Yii::t('audit', 'Action'),
            'model'     => Yii::t('audit', 'Type'),
            'field'     => Yii::t('audit', 'Field'),
            'stamp'     => Yii::t('audit', 'Stamp'),
            'model_id'  => Yii::t('audit', 'ID'),
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['audit_id', 'action', 'model', 'stamp', 'model_id'], 'required'],
            ['audit_id', 'integer', 'integerOnly' => true],
            ['action', 'string', 'max' => 255],
            ['model', 'string', 'max' => 255],
            ['field', 'string', 'max' => 255],
            ['model_id', 'string', 'max' => 255],
            [['old_value', 'new_value'], 'safe']
        ];
    }

    public static function recently($query)
    {
        $query->orderBy(['[[stamp]]' => SORT_DESC]);
    }

    public function getParent()
    {
        return new $this->model;
    }
}
