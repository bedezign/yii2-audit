<?php
/* @var $panel MailPanel */

use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\panels\MailPanel;
use dosamigos\chartjs\ChartJs;

$defaults = [];
$startDate = strtotime('-6 days');
foreach (range(-6, 0) as $day) {
    $defaults[date('D: Y-m-d', strtotime($day . 'days'))] = 0;
}
$results = AuditMail::find()
    ->select(["COUNT(DISTINCT id) as count", "DATE_FORMAT(created, '%a: %Y-%m-%d') AS day"])
    ->where(['between', 'created',
        date('Y-m-d 00:00:00', $startDate),
        date('Y-m-d 23:59:59')])
    ->groupBy("day")->indexBy('day')->column();
$results = array_merge($defaults, $results);

echo ChartJs::widget([
    'type' => 'bar',
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
