<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditEntry;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('canvas {width: 100% !important;height: 400px;}');
?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <h2><?php echo Html::a(Yii::t('audit', 'Entries'), ['entry/index']); ?></h2>

            <div class="well">
                <?php
                //initialise defaults (0 entries) for each day
                $defaults = [];
                $startDate = strtotime('-6 days');
                foreach (range(-6, 0) as $day) {
                    $defaults[date('D: Y-m-d', strtotime($day . 'days'))] = 0;
                }

                $results = AuditEntry::find()
                    ->select(["COUNT(DISTINCT id) as count", "created AS day"])
                    ->where(['between', 'created',
                        date('Y-m-d 00:00:00', $startDate),
                        date('Y-m-d 23:59:59')])
                    ->groupBy("day")->indexBy('day')->column();

                // format dates properly
                $formattedData = [];
                foreach ($results as $date => $count) {
                    $date = date('D: Y-m-d', strtotime($date));
                    $formattedData[$date] = $count;
                }
                $results = $formattedData;

                // replace defaults with data from db where available
                $results = array_merge($defaults, $results);

                echo ChartJs::widget([
                    'type' => 'bar',
                    'options' => [
                        'height' => '45',
                    ],
                    'clientOptions' => [
                        'legend' => ['display' => false],
                        'tooltips' => ['enabled' => false],
                    ],
                    'data' => [
                        'labels' => array_keys($results),
                        'datasets' => [
                            [
                                'fillColor' => 'rgba(151,187,205,0.5)',
                                'strokeColor' => 'rgba(151,187,205,1)',
                                'pointColor' => 'rgba(151,187,205,1)',
                                'pointStrokeColor' => '#fff',
                                'data' => array_values($results),
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>

        <?php
        foreach (Audit::getInstance()->panels as $panel) {
            /** @var Panel $panel */
            $chart = $panel->getChart();
            if (!$chart) {
                continue;
            }
            $indexUrl = $panel->getIndexUrl();
            ?>
            <div class="col-md-3 col-lg-3">
                <h2><?php echo $indexUrl ? Html::a($panel->getName(), $indexUrl) : $panel->getName(); ?></h2>

                <div class="well">
                    <?php echo $chart; ?>
                </div>
            </div>
        <?php } ?>

    </div>

</div>
