<?php

namespace tests\app\controllers;

use yii\web\Controller;

/**
 * SiteController
 * @package tests\app\controllers
 */
class SiteController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionInfo()
    {
        return $this->render('info');
    }

}