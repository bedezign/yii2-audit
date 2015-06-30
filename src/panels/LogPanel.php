<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\PanelTrait;
use Yii;
use yii\debug\models\search\Log;
use yii\grid\GridViewAsset;

/**
 * LogPanel
 * @package bedezign\yii2\audit\panels
 */
class LogPanel extends \yii\debug\panels\LogPanel
{
    use PanelTrait;

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data['messages']) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Log();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/log/detail', [
            'dataProvider' => $dataProvider,
            'panel' => $this,
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