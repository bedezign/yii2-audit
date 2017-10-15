<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\panels\RendersSummaryChartTrait;
use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use Yii;

/**
 * DefaultController
 * @package bedezign\yii2\audit\controllers
 */
class DefaultController extends Controller
{
    use RendersSummaryChartTrait;

    /**
     * Module Default Action.
     * @return mixed
     */
    public function actionIndex()
    {
        $chartData = $this->getChartData();
        return $this->render('index', ['chartData' => $chartData]);
    }

    protected function getChartModel()
    {
        return AuditEntry::className();
    }
}
