<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\DataStoragePanelTrait;
use bedezign\yii2\audit\models\AuditData;
use Yii;
use yii\debug\models\search\Db;
use yii\grid\GridViewAsset;

/**
 * DbPanel
 * @package bedezign\yii2\audit\panels
 *
 * @method bool hasExplain()
 */
class DbPanel extends \yii\debug\panels\DbPanel
{
    use DataStoragePanelTrait;

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        $timings = $this->calculateTimings();
        $queryCount = count($timings);
        $queryTime = number_format($this->getTotalQueryTime($timings) * 1000) . ' ms';
        return $this->getName() . ' <small>(' . $queryCount . ' / ' . $queryTime . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Db();
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams(), $this->getModels());

        return Yii::$app->view->render('@yii/debug/views/default/panels/db/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'hasExplain' => method_exists($this, 'hasExplain') ? $this->hasExplain() : null,
        ]);
    }

    public function save()
    {
        $data = parent::save();
        return (isset($data['messages']) && count($data['messages']) > 0) ? $data : null;
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
            ':type' => 'audit/db',
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]) !== false;
    }

}