<?php

namespace tests\app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm
 * @package tests\app\models
 */
class ContactForm extends Model
{
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $email;
    /**
     * @var
     */
    public $subject;
    /**
     * @var
     */
    public $body;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
        ];
    }

    /**
     * @param $email
     * @return bool
     */
    public function sendEmail($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();
            return true;
        } else {
            return false;
        }
    }
}
