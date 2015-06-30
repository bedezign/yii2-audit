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
     * @return string
     */
    public function getName()
    {
        return Yii::t('audit', 'Extra');
    }

    /**
     * @return string
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
     * @return array|mixed
     */
    public function save()
    {
        return $this->data;
    }

    /**
     * @return string
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
     *
     */
    public function registerAssets()
    {
        GridViewAsset::register(Yii::$app->getView());
    }

}