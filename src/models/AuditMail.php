<?php
/**
 * This model allows for storing of mail entries linked to a specific audit entry
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\db\ActiveRecord;
use bedezign\yii2\audit\components\Helper;
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
 * @property string $headers
 * @property string $body
 * @property string $data
 *
 * @property AuditEntry    $entry
 */
class AuditMail extends ActiveRecord
{

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
            /* @var $swiftMessage \Swift_Message */
            $swiftMessage = $message->getSwiftMessage();

            $body = $swiftMessage->getBody();
            if (empty($body)) {
                $parts = $swiftMessage->getChildren();
                foreach ($parts as $part) {
                    if (!($part instanceof \Swift_Mime_Attachment)) {
                        /* @var $part \Swift_Mime_MimePart */
                        if ($part->getContentType() == 'text/plain') {
                            $messageData['charset'] = $part->getCharset();
                            $body = $part->getBody();
                            break;
                        }
                    }
                }
            }

            $mail->body = Helper::serialize($body, false);
            $mail->headers = Helper::serialize($swiftMessage->getHeaders(), false);
        }

        $mail->data = Helper::serialize($message->toString(), false);

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
            'headers' => Yii::t('audit', 'Headers'),
            'body' => Yii::t('audit', 'Body'),
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