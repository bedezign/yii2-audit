<?php
/* @var $panel MailPanel */

use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\panels\MailPanel;
use dosamigos\chartjs\ChartJs;

$days = [];
$count = [];
foreach (range(-6, 0) as $day) {
    $date = strtotime($day . 'days');
    $days[] = date('D: Y-m-d', $date);
    $count[] = AuditMail::find()->where(['between', 'created', date('Y-m-d 00:00:00', $date), date('Y-m-d 23:59:59', $date)])->count();
}

echo ChartJs::widget([
    'type' => 'bar',
    'clientOptions' => [
        'legend' => ['display' => false],
        'tooltips' => ['enabled' => false],
    ],
    'data' => [
        'labels' => $days,
        'datasets' => [
            [
                'fillColor' => 'rgba(151,187,205,0.5)',
                'strokeColor' => 'rgba(151,187,205,1)',
                'pointColor' => 'rgba(151,187,205,1)',
                'pointStrokeColor' => '#fff',
                'data' => $count,
            ],
        ],
    ]
]);
