<?php

namespace bedezign\yii2\audit\providers;


use bedezign\yii2\audit\Auditing;
use bedezign\yii2\audit\components\Helper;
use Yii;
use yii\helpers\ArrayHelper;

class HeaderProvider
{

    public $logVars = [
        'request',
        'response',
    ];

    public function record()
    {
        if (in_array('request', $this->logVars)) {
            $entry = Auditing::current()->getEntry();
            if ($entry) {
                if (Yii::$app->request instanceof \yii\web\Request) {
                    $data = Helper::compact(Yii::$app->request->headers, true);
                    if ($data) {
                        $entry->addData('request_headers', $data);
                    }
                }
            }
        }
    }

    public function finalize()
    {
        if (in_array('response', $this->logVars)) {
            $entry = Auditing::current()->getEntry();
            if ($entry) {
                if (Yii::$app->response instanceof \yii\web\Response) {
                    $data = Helper::compact(Yii::$app->response->headers, true);
                    if ($data) {
                        $entry->addData('response_headers', $data);
                    }
                }
            }
        }
    }

}