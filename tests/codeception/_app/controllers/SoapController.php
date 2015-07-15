<?php

namespace tests\app\controllers;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\SoapClient;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * SoapController
 * @package tests\app\controllers
 */
class SoapController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionZip()
    {
        $zipCodes = [10065, 94062, 90402, 94024, 95070, 90027, 90210, 95014, 77005, 2109, 20008];
        $options =
        [
            'trace' => 1,
            'exception' => 1,
            'connection_timeout' => 10,
        ];
        try {
            $client = new SoapClient('http://www.webservicex.net/uszip.asmx?wsdl', $options);
            $client->GetInfoByZIP(['USZip' => $zipCodes[rand(0, count($zipCodes) - 1)]]);

            $entry = Audit::getInstance()->getEntry()->id;
            \Yii::$app->session->addFlash('info', 'SOAP call done. <a href="' . Url::to(['audit/entry/view', 'id' => $entry, 'panel' => 'audit/soap']) . '">Click here for the entry</a>');
        }
        catch (\Exception $e) {
            \Yii::$app->session->addFlash('error', 'Unfortunately something went wrong (' . $e->getMessage() . '), please try again later');
        }

        $this->redirect(['index']);
    }

}