<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\models\AuditData;
use Yii;
use bedezign\yii2\audit\components\panels\DataStoragePanel;
use yii\data\ArrayDataProvider;
use yii\grid\GridViewAsset;

/**
 * ExtraDataPanel
 * @package bedezign\yii2\audit\panels
 */
class ExtraDataPanel extends DataStoragePanel
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module->registerFunction('data', [$this, 'trackData']);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('audit', 'Extra Data');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @param $type
     * @param $data
     */
    public function trackData($type, $data)
    {
        $this->module->getEntry(true);
        if (!is_array($this->data))
            $this->data = [];

        $this->data[] = ['type' => $type, 'data' => $data];
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $dataProvider = new ArrayDataProvider();
        $dataProvider->allModels = $this->data;

        return Yii::$app->view->render('panels/extra/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

    /**
     * @inheritdoc
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditData::deleteAll('type = :type AND created <= :created', [
            ':type' => 'audit/extra',
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]);
    }

}