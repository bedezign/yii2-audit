<?php

namespace bedezign\yii2\audit\panels;
use bedezign\yii2\audit\components\panels\Panel;
use Yii;
use yii\data\ArrayDataProvider;
use yii\grid\GridViewAsset;

/**
 * ExtraDataPanel
 * @package bedezign\yii2\audit\panels
 */
class ExtraDataPanel extends Panel
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('audit', 'Extra');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @param $data
     */
    public function trackData($data)
    {
        if (!is_array($this->data))
            $this->data = [];

        $this->data[] = $data;
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

}