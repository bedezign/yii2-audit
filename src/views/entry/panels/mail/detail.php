<?php
/* @var $panel LogPanel */
/* @var $searchModel AuditMailSearch */
/* @var $dataProvider ArrayDataProvider */

use bedezign\yii2\audit\models\AuditMailSearch;
use yii\data\ArrayDataProvider;
use yii\debug\panels\LogPanel;
use yii\grid\GridView;

echo '<h1>' . Yii::t('audit', 'Email Messages') . '</h1>';

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
        [
            'attribute' => 'id',
            'options' => [
                'width' => '80px',
            ],
        ],
        [
            'attribute' => 'successful',
            'options' => [
                'width' => '80px',
            ],
        ],
        'to',
        'from',
        'reply',
        'cc',
        'bcc',
        'subject',
        [
            'attribute' => 'created',
            'options' => [
                'width' => '150px',
            ],
        ],
    ],
]);
