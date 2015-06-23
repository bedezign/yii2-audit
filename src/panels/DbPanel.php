<?php

namespace bedezign\yii2\audit\panels;

use yii\debug\models\search\Db;

class DbPanel extends \yii\debug\panels\DbPanel
{
    public function getDetail()
    {
        $searchModel = new Db();
        $dataProvider = $searchModel->search(\Yii::$app->request->getQueryParams(), $this->getModels());

        return \Yii::$app->view->render('@yii/debug/views/default/panels/db/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'hasExplain' => $this->hasExplain()
        ]);
    }
}