<?php
/* @var $panel JavascriptPanel */

use bedezign\yii2\audit\panels\JavascriptPanel;
use dosamigos\chartjs\ChartJs;

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
                'data' => array_keys($chartData),
            ],
        ],
    ]
]);
