<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;
use Yii;
use yii\db\Exception;

/**
 * AuditMailTest
 */
class AuditMailTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testGetEntry()
    {
        $mail = AuditMail::findOne(1);
        $this->assertEquals($mail->getEntry()->one()->className(), AuditEntry::className());
    }

    public function testAddManualMail()
    {
        $oldId = $this->getLastPk(AuditMail::className());

        $message = [
            'subject' => 'test email subject',
            'text' => 'test email text',
            'html' => 'test email <b>html</b>',
        ];
        Yii::$app->mailer->compose()
            ->setFrom(['from@example.com' => Yii::$app->name])
            ->setTo(['to@example.com' => Yii::$app->name])
            ->setReplyTo(['reply@example.com' => Yii::$app->name])
            ->setCc(['cc@example.com' => Yii::$app->name])
            ->setBcc(['bcc@example.com' => Yii::$app->name])
            ->setSubject($message['subject'])
            ->setTextBody($message['text'])
            ->setHtmlBody($message['html'])
            ->send();

        $newId = $this->getLastPk(AuditMail::className());
        $this->assertNotEquals($oldId, $newId, 'Expected mail entry to be created');

        $this->assertInstanceOf(AuditMail::className(), $mail = AuditMail::findOne($newId));
        $this->assertEquals('from@example.com', $mail->from);
        $this->assertEquals('to@example.com', $mail->to);
        $this->assertEquals('reply@example.com', $mail->reply);
        $this->assertEquals('cc@example.com', $mail->cc);
        $this->assertEquals('bcc@example.com', $mail->bcc);
        $this->assertEquals($message['subject'], $mail->subject);
        $this->assertEquals($message['text'], $mail->text);
        $this->assertEquals($message['html'], $mail->html);
    }

}