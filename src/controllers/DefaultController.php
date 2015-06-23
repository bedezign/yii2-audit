<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use Yii;

/**
 * DefaultController
 * @package bedezign\yii2\audit\controllers
 */
class DefaultController extends Controller
{
    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

}
