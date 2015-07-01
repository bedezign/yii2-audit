<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditErrorSearch;
use Yii;
use yii\grid\GridViewAsset;

/**
 * ErrorPanel
 * @package bedezign\yii2\audit\panels
 */
class ErrorPanel extends Panel
{
    public function init()
    {
        parent::init();
        $this->module->registerFunction('exception', function(\Exception $e) {
            $entry = $this->module->getEntry(true);
            return $entry ? AuditError::log($entry, $e) : false;
        });

        $this->module->registerFunction('errorMessage', function ($message, $code = 0, $file = '', $line = 0, $trace = []) {
            $entry = $this->module->getEntry(true);
            return $entry ? AuditError::logMessage($entry, $message, $code, $file, $line, $trace) : false;
        });
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('audit', 'Error');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->linkedErrors) . ')</small>';
    }

    /**
     * @inheritdoc
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

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

}