<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\debug\models\search\Db;
use yii\debug\models\search\Log;

class LogPanel extends \yii\debug\panels\LogPanel
{
    public function getDetail()
    {
        $searchModel = new Log();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/log/detail', [
            'dataProvider' => $dataProvider,
            'panel' => $this,
            'searchModel' => $searchModel,
        ]);
    }
}