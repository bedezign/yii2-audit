<?php
/**
 * This model allows for storing of mail entries linked to a specific audit entry
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\db\ActiveRecord;
use bedezign\yii2\audit\components\Helper;
use Swift_Message;
use Swift_Mime_Attachment;
use Swift_Mime_MimePart;
use Yii;
use yii\base\Event;
use yii\mail\MessageInterface;
use yii\swiftmailer\Message;

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
        return '{{audit_mail}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    /**
     * @param Event $event
     * @return null|static
     */
    public static function record($event)
    {
        /* @var $message MessageInterface */
        $message = $event->message;
        $entry = Audit::getInstance()->getEntry(true);

        $mail = new static();
        $mail->entry_id = $entry->id;
        $mail->successful = $event->isSuccessful;
        $mail->from = self::convertParams($message->getFrom());
        $mail->to = self::convertParams($message->getTo());
        $mail->reply = self::convertParams($message->getReplyTo());
        $mail->cc = self::convertParams($message->getCc());
        $mail->bcc = self::convertParams($message->getBcc());
        $mail->subject = $message->getSubject();

        // add more information when message is a SwiftMailer message
        if ($message instanceof Message) {
            /* @var $swiftMessage Swift_Message */
            $swiftMessage = $message->getSwiftMessage();
            if ($swiftMessage->getContentType() == 'text/html') {
                $mail->html = $swiftMessage->getBody();
            } else {
                $mail->text = $swiftMessage->getBody();
            }
            foreach ($swiftMessage->getChildren() as $part) {
                /* @var $part Swift_Mime_MimePart */
                if ($part instanceof Swift_Mime_Attachment) {
                    continue;
                }
                $contentType = $part->getContentType();
                if ($contentType == 'text/plain') {
                    $mail->text = $part->getBody();
                } elseif ($contentType == 'text/html') {
                    $mail->html = $part->getBody();
                }
            }
        }

        $mail->data = $message->toString();

        return $mail->save(false) ? $mail : null;
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

    /**
     * @param $attr
     * @return string
     */
    private static function convertParams($attr)
    {
        if (is_array($attr)) {
            $attr = implode(', ', array_keys($attr));
        }
        return $attr;
    }
}