<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * The followings are the available columns in table 'tbl_audit_trail':
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 * @property string  $action
 * @property string  $model
 * @property string  $model_id
 * @property string  $field
 * @property string  $new_value
 * @property string  $old_value
 * @property string  $created
 */
class AuditTrail extends ActiveRecord
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
            'action'    => Yii::t('audit', 'Action'),
            'model'     => Yii::t('audit', 'Type'),
            'model_id'  => Yii::t('audit', 'ID'),
            'field'     => Yii::t('audit', 'Field'),
            'old_value' => Yii::t('audit', 'Old Value'),
            'new_value' => Yii::t('audit', 'New Value'),
            'created'   => Yii::t('audit', 'Created'),
        ];
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['action', 'model', 'created', 'model_id'], 'required'],
            [['entry_id', 'user_id'], 'integer', 'integerOnly' => true],
            [['action', 'model', 'model_id', 'field'], 'string', 'max' => 255],
            [['old_value', 'new_value'], 'safe']
        ];
    }

    /**
     * @param ActiveQuery $query
     */
    public static function recently($query)
    {
        $query->orderBy(['[[id]]' => SORT_DESC]);
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
