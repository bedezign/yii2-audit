<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\debug\models\search\Profile;

/**
 * ProfilingPanel
 * @package bedezign\yii2\audit\panels
 */
class ProfilingPanel extends \yii\debug\panels\ProfilingPanel
{
    use PanelHelperTrait;

    /**
     * @return string
     */
    public function getLabel()
    {
        $memory = sprintf('%.1f MB', $this->data['memory'] / 1048576);
        $time = number_format($this->data['time'] * 1000) . ' ms';
        return $this->getName() . ' <small>(' . $memory . ' / ' . $time . ')</small>';
    }


    /**
     * @return string
     */
    public function getDetail()
    {
        $searchModel = new Profile();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/profile/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'memory' => sprintf('%.1f MB', $this->data['memory'] / 1048576),
            'time' => number_format($this->data['time'] * 1000) . ' ms',
        ]);
    }
}