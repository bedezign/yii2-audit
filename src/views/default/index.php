<?php

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditTrail;
use dosamigos\chartjs\ChartJs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('canvas {width: 100% !important;height: 400px;}');

$dataSet = [
    'fillColor' => "rgba(151,187,205,0.5)",
    'strokeColor' => "rgba(151,187,205,1)",
    'pointColor' => "rgba(151,187,205,1)",
    'pointStrokeColor' => "#fff",
    //'data' => $count['entry'],
];
$options = [
    'height' => 400,
    'width' => 400
];
$days = [];
$count = [
    'entry' => [],
    'trail' => [],
    'javascript' => [],
    'error' => [],
];
foreach (range(-6, 0) as $day) {
    $date = strtotime($day . 'days');
    $days[] = date('D: Y-m-d', $date);
    $count['entry'][] = AuditEntry::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
    $count['error'][] = AuditError::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
    $count['trail'][] = AuditTrail::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
    $count['javascript'][] = AuditJavascript::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
}

?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6 col-lg-3">
            <h2><?php echo Html::a(Yii::t('audit', 'Entries'), ['entry/index']); ?></h2>

            <div class="well">
                <?php
                $this->registerCss('canvas {width: 100% !important;height: 400px;}');
                echo ChartJs::widget([
                    'type' => 'Line',
                    'options' => $options,
                    'data' => [
                        'labels' => $days,
                        'datasets' => [ArrayHelper::merge($dataSet, ['data' => $count['entry']])],
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <h2><?php echo Html::a(Yii::t('audit', 'Trails'), ['trail/index']); ?></h2>

            <div class="well">
                <?php
                $this->registerCss('canvas {width: 100% !important;height: 400px;}');
                echo ChartJs::widget([
                    'type' => 'Line',
                    'options' => $options,
                    'data' => [
                        'labels' => $days,
                        'datasets' => [ArrayHelper::merge($dataSet, ['data' => $count['trail']])],
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <h2><?php echo Html::a(Yii::t('audit', 'JS'), ['javascript/index']); ?></h2>

            <div class="well">
                <?php
                echo ChartJs::widget([
                    'type' => 'Line',
                    'options' => $options,
                    'data' => [
                        'labels' => $days,
                        'datasets' => [ArrayHelper::merge($dataSet, ['data' => $count['javascript']])],
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <h2><?php echo Html::a(Yii::t('audit', 'Errors'), ['error/index']); ?></h2>

            <div class="well">
                <?php
                echo ChartJs::widget([
                    'type' => 'Line',
                    'options' => $options,
                    'data' => [
                        'labels' => $days,
                        'datasets' => [ArrayHelper::merge($dataSet, ['data' => $count['error']])],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>

</div>
