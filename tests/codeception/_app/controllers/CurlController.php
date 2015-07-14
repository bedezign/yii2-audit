<?php

namespace tests\app\controllers;

use bedezign\yii2\audit\Audit;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * CurlController
 * @package tests\app\controllers
 */
class CurlController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGoogle()
    {
        $this->doCurl('http://google.com');
    }

    public function actionQuoteOfTheDay()
    {
        $this->doCurl('http://api.theysaidso.com/qod.json');
    }

    public function actionQuoteOfTheDayXml()
    {
        $this->doCurl('http://api.theysaidso.com/qod.xml');
    }

    protected function doCurl($url)
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_FOLLOWLOCATION, 1);
        Audit::getInstance()->curlExec($handle, $url);

        $entry = Audit::getInstance()->getEntry()->id;
        \Yii::$app->session->addFlash('info', 'cURL request done. <a href="' . Url::to(['audit/entry/view', 'id' => $entry, 'panel' => 'audit/curl']) . '">Click here for the entry</a>');
        $this->redirect(['index']);
    }

}