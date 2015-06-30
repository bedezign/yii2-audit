<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditTrailSearch;
use Yii;
use yii\grid\GridViewAsset;

/**
 * TrailPanel
 * @package bedezign\yii2\audit\panels
 */
class TrailPanel extends Panel
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('audit', 'Trail');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->trails) . ')</small>';
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

}