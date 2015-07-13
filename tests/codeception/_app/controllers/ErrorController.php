<?php

namespace tests\app\controllers;

use yii\web\Controller;

/**
 * ErrorController
 * @package tests\app\controllers
 */
class ErrorController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionUndefinedVariable()
    {
        echo $foobar;
    }

    public function actionUndefinedFunction()
    {
        foobar();
    }

    public function actionMethodNonObject()
    {
        $foo = 'foo';
        $foo->bar();
    }

}