<?php

namespace bedezign\yii2\audit\panels;

class RequestPanel extends \yii\debug\panels\RequestPanel
{
    public function getDetail()
    {
        return \Yii::$app->view->render('@yii/debug/views/default/panels/request/detail', ['panel' => $this]);
    }
}