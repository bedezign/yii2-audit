<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\models\AuditErrorSearch;
use yii\debug\Panel;

/**
 * ErrorPanel
 * @package bedezign\yii2\audit\panels
 */
class ErrorPanel extends Panel
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('audit', 'Error');
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        $searchModel = new AuditErrorSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditErrorSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/error/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}