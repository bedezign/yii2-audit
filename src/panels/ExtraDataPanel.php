<?php

namespace bedezign\yii2\audit\panels;

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
        return \Yii::t('audit', 'Extra');
    }

    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    public function trackData($data)
    {
        if (!is_array($this->data))
            $this->data = [];

        $this->data[] = $data;
    }

    public function save()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        $dataProvider = new \yii\data\ArrayDataProvider();
        $dataProvider->allModels = $this->data;

        return \Yii::$app->view->render('panels/extra/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
        ]);
    }
}