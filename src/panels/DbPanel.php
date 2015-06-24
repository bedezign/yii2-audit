<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\debug\models\search\Db;

/**
 * DbPanel
 * @package bedezign\yii2\audit\panels
 */
class DbPanel extends \yii\debug\panels\DbPanel
{
    /**
     * @return string
     */
    public function getDetail()
    {
        $searchModel = new Db();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/db/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            //'hasExplain' => $this->hasExplain(),
        ]);
    }
}