<?php
/* @var $panel yii\debug\panels\LogPanel */

use yii\helpers\Html;
use yii\grid\GridView;

echo '<h1>' . Yii::t('audit', 'Custom Data') . '</h1>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id'           => 'log-panel-detailed-grid',
    'options'      => ['class' => 'detail-grid-view table-responsive'],
    'columns'      => [
        'type',
        [
            'header' => Yii::t('audit', 'Data'),
            'value' => function ($data) {
                return \yii\helpers\VarDumper::dumpAsString($data['data']);
            }
        ]
    ]
]);
