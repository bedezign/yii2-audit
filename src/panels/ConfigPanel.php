<?php

namespace bedezign\yii2\audit\panels;

use Yii;

class ConfigPanel extends \yii\debug\panels\ConfigPanel
{
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/config/detail', [
            'panel' => $this,
        ]);
    }
}