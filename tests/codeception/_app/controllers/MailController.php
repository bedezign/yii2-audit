<?php

namespace tests\app\controllers;

use tests\app\models\ContactForm;
use Yii;
use yii\web\Controller;

/**
 * MailController
 * @package tests\app\controllers
 */
class MailController extends Controller
{

    public function actionIndex()
    {
        $contactForm = new ContactForm();
        if ($contactForm->load(Yii::$app->request->post()) && $contactForm->sendEmail('admin@example.com')) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Mail has been sent.'));
            return $this->refresh();
        }
        return $this->render('index', [
            'contactForm' => $contactForm,
        ]);
    }

}