<?php

namespace tests\app\controllers;

use yii\web\Controller;

/**
 * MailController
 * @package tests\app\controllers
 */
class MailController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}