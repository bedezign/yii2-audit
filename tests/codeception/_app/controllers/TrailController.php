<?php

namespace tests\app\controllers;

use yii\web\Controller;

/**
 * TrailController
 * @package tests\app\controllers
 */
class TrailController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

}