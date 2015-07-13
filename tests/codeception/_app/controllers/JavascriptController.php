<?php

namespace tests\app\controllers;

use yii\web\Controller;

/**
 * JavascriptController
 * @package tests\app\controllers
 */
class JavascriptController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}