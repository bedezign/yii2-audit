<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\DataStoragePanelTrait;
use Yii;
use yii\debug\models\search\Profile;
use yii\grid\GridViewAsset;

/**
 * ProfilingPanel
 * @package bedezign\yii2\audit\panels
 */
class ProfilingPanel extends \yii\debug\panels\ProfilingPanel
{
    use DataStoragePanelTrait;

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $memory = isset($this->data['memory']) ? sprintf('%.1f MB', $this->data['memory'] / 1048576) : '';
        $time = isset($this->data['time']) ? number_format($this->data['time'] * 1000) . ' ms' : '';
        return $this->getName() . ' <small>(' . $memory . ' / ' . $time . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Profile();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/profile/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'memory' => isset($this->data['memory']) ? sprintf('%.1f MB', $this->data['memory'] / 1048576) : '',
            'time' => isset($this->data['time']) ? number_format($this->data['time'] * 1000) . ' ms' : '',
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