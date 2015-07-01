<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use Yii;

class TestController extends Controller
{
    public function actionIndex()
    {
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
    }
}
