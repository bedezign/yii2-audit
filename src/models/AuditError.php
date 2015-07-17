<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditError
 * @package bedezign\yii2\audit\models
 *
 * @property int           $id
 * @property int           $entry_id
 * @property string        $created
 * @property string        $message
 * @property int           $code
 * @property string        $file
 * @property int           $line
 * @property mixed         $trace
 * @property string        $hash
 * @property int           $emailed
 *
 * @property AuditEntry    $entry
 */
class AuditError extends ActiveRecord
{
    /**
     * @var array
     */
    protected $serializeAttributes = ['trace'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_error}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('audit', 'ID'),
            'entry_id'  => Yii::t('audit', 'Entry ID'),
            'created'   => Yii::t('audit', 'Created'),
            'message'   => Yii::t('audit', 'Message'),
            'code'      => Yii::t('audit', 'Error Code'),
            'file'      => Yii::t('audit', 'File'),
            'line'      => Yii::t('audit', 'Line'),
            'trace'     => Yii::t('audit', 'Trace'),
            'hash'      => Yii::t('audit', 'Hash'),
            'emailed'   => Yii::t('audit', 'Emailed'),
        ];
    }

}
