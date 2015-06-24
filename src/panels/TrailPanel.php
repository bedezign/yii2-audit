<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\models\AuditTrailSearch;
use yii\debug\Panel;

class TrailPanel extends Panel
{
    public function getName()
    {
        return \Yii::t('audit', 'Trail');
    }

    public function getDetail()
    {
        $searchModel = new AuditTrailSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditTrailSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/trail/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}