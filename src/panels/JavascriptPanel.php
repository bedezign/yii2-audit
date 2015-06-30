<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditJavascriptSearch;
use Yii;
use yii\grid\GridViewAsset;

/**
 * JavascriptPanel
 * @package bedezign\yii2\audit\panels
 */
class JavascriptPanel extends Panel
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('audit', 'Javascript');
    }

    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->javascripts) . ')</small>';
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        $searchModel = new AuditJavascriptSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditJavascriptSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/javascript/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     *
     */
    public function registerAssets()
    {
        GridViewAsset::register(Yii::$app->getView());
    }

}