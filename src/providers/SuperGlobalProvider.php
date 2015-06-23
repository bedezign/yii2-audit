<?php

namespace bedezign\yii2\audit\providers;


use bedezign\yii2\audit\Audit;
use Yii;
use yii\helpers\ArrayHelper;

class SuperGlobalProvider
{

    public $logVars = [
        '_GET',
        '_POST',
        '_COOKIE',
        '_SERVER',
        '_FILES',
        '_SESSION',
        '_PARAMS',
    ];

    public function record()
    {
        $dataMap = [
            '_GET' => $_GET,
            '_POST' => $_POST,
            '_COOKIE' => $_COOKIE,
            '_SERVER' => $_SERVER,
            '_FILES' => $_FILES,
        ];

        if (Yii::$app->request instanceof \yii\web\Request) {
            if (!empty($_SESSION)) {
                $dataMap['_SESSION'] = $_SESSION;
            }
        } else if (Yii::$app->request instanceof \yii\console\Request) {
            $dataMap['_PARAMS'] = Yii::$app->request->params;
        }

        $entry = Audit::current()->getEntry();
        if ($entry) {
            $batchData = [];
            foreach ($this->logVars as $type) {
                $data = ArrayHelper::getValue($dataMap, $type);
                if ($data) {
                    $batchData[$type] = $data;
                }
            }
            $entry->addBatchData($batchData);
        }

    }
}