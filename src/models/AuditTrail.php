<?php

namespace bedezign\yii2\audit\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * The followings are the available columns in table 'tbl_audit_trail':
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 * @property string  $new_value
 * @property string  $old_value
 * @property string  $action
 * @property string  $model
 * @property string  $field
 * @property string  $stamp
 * @property string  $model_id
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
            'entry_id'  => Yii::t('audit', 'Entry ID'),
            'user_id'   => Yii::t('audit', 'User ID'),
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
            [['action', 'model', 'stamp', 'model_id'], 'required'],
            [['entry_id', 'user_id'], 'integer', 'integerOnly' => true],
            ['action', 'string', 'max' => 255],
            ['model', 'string', 'max' => 255],
            ['field', 'string', 'max' => 255],
            ['model_id', 'string', 'max' => 255],
            [['old_value', 'new_value'], 'safe']
        ];
    }

    /**
     * @param $query
     */
    public static function recently($query)
    {
        $query->orderBy(['[[stamp]]' => SORT_DESC]);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return new $this->model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    /**
     * @return mixed
     */
    public function getDiffHtml()
    {
        $old = explode("\n", $this->old_value);
        $new = explode("\n", $this->new_value);

        foreach ($old as $i => $line) {
            $old[$i] = rtrim($line, "\r\n");
        }
        foreach ($new as $i => $line) {
            $new[$i] = rtrim($line, "\r\n");
        }

        $diff = new \Diff($old, $new);
        return $diff->render(new \Diff_Renderer_Html_Inline);
    }

}
