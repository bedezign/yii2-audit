<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\DataStoragePanelTrait;
use bedezign\yii2\audit\models\AuditData;
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
        $memory = sprintf('%.1f MB', $this->data['memory'] / 1048576);
        $time = number_format($this->data['time'] * 1000) . ' ms';
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
            'memory' => sprintf('%.1f MB', $this->data['memory'] / 1048576),
            'time' => number_format($this->data['time'] * 1000) . ' ms',
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
            ':type' => 'audit/profiling',
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]);
    }

}