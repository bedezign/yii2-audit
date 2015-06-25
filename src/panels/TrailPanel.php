<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\models\AuditTrailSearch;

/**
 * TrailPanel
 * @package bedezign\yii2\audit\panels
 */
class TrailPanel extends AuditBasePanel
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('audit', 'Trail');
    }

    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->trails) . ')</small>';
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        $searchModel = new AuditTrailSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditTrailSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/trail/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }
}