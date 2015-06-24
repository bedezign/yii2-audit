<?php
/* @var $panel yii\debug\panels\LogPanel */
/* @var $searchModel yii\debug\models\search\Log */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\VarDumper;
use yii\log\Logger;

?>
    <h1>Errors</h1>
<?php

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id' => 'log-panel-detailed-grid',
    'options' => ['class' => 'detail-grid-view table-responsive'],
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']),
                        ['error/view', 'id' => $model->id],
                        [
                            'class' => '',
                            'data' => [
                                'toggle' => 'modal',
                            ]
                        ]
                    );
                }
            ]
        ],
        [
            'attribute' => 'id',
            'options' => [
                'width' => '80px',
            ],
        ],
        'message',
        [
            'attribute' => 'code',
            'options' => [
                'width' => '80px',
            ],
        ],
        'file',
        'message',
        [
            'attribute' => 'line',
            'options' => [
                'width' => '80px',
            ],
        ],
    ],
]);
