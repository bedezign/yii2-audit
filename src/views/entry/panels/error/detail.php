<?php
/* @var $panel yii\debug\panels\LogPanel */
/* @var $searchModel yii\debug\models\search\Log */
/* @var $dataProvider yii\data\ArrayDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use bedezign\yii2\audit\models\AuditErrorSearch;

echo Html::tag('h1', $panel->name);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'id'           => 'log-panel-detailed-grid',
    'options'      => ['class' => 'detail-grid-view table-responsive'],
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, $model) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']), ['error/view', 'id' => $model->id]
                    );
                }
            ],
            'options'  => [
                'width' => '30px',
            ],
        ],
        [
            'attribute' => 'id',
            'options'   => [
                'width' => '80px',
            ],
        ],
        'message',
        [
            'attribute' => 'code',
            'options'   => [
                'width' => '80px',
            ],
        ],
        [
            'attribute' => 'file',
            'filter' => AuditErrorSearch::fileFilter(),
        ],
        [
            'attribute' => 'line',
            'options'   => [
                'width' => '80px',
            ],
        ],
        [
            'attribute' => 'hash',
            'options' => [
                'width' => '100px',
            ],
        ],
    ],
]);
