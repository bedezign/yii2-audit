<?php
/**
 * This model allows for storing of mail entries linked to a specific audit entry
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditMail
 *
 * @package bedezign\yii2\audit\models
 * @property int    $id
 * @property int    $entry_id
 * @property string $created
 * @property int    $successful
 * @property string $from
 * @property string $to
 * @property string $reply
 * @property string $cc
 * @property string $bcc
 * @property string $subject
 * @property string $text
 * @property string $html
 * @property string $data
 *
 * @property AuditEntry    $entry
 */
class AuditMail extends ActiveRecord
{
    protected $serializeAttributes = ['text', 'html', 'data'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_mail}}';
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
            'id' => Yii::t('audit', 'ID'),
            'entry_id' => Yii::t('audit', 'Entry ID'),
            'created' => Yii::t('audit', 'Created'),
            'successful' => Yii::t('audit', 'Successful'),
            'from' => Yii::t('audit', 'From'),
            'to' => Yii::t('audit', 'To'),
            'reply' => Yii::t('audit', 'Reply'),
            'cc' => Yii::t('audit', 'CC'),
            'bcc' => Yii::t('audit', 'BCC'),
            'subject' => Yii::t('audit', 'Subject'),
            'text' => Yii::t('audit', 'Text Body'),
            'html' => Yii::t('audit', 'HTML Body'),
            'data' => Yii::t('audit', 'Data'),
        ];
    }

}