<?php
/* @var $panel TrailPanel */

use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\panels\TrailPanel;
use dosamigos\chartjs\ChartJs;

//initialise defaults (0 entries) for each day
$defaults = [];
$startDate = strtotime('-6 days');
foreach (range(-6, 0) as $day) {
    $defaults[date('D: Y-m-d', strtotime($day . 'days'))] = 0;
}

$results = AuditTrail::find()
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
    'clientOptions' => [
        'legend' => ['display' => false],
        'tooltips' => ['enabled' => false],
    ],
    'data' => [
        'labels' => array_keys($chartData),
        'datasets' => [
            [
                'fillColor' => 'rgba(151,187,205,0.5)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => array_values($chartData),
            ],
        ],
    ]
]);
