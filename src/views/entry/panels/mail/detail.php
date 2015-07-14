<?php
/* @var $panel MailPanel */
/* @var $searchModel AuditMailSearch */
/* @var $dataProvider ArrayDataProvider */

use bedezign\yii2\audit\models\AuditMailSearch;
use bedezign\yii2\audit\panels\MailPanel;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;

echo Html::tag('h1', $panel->name);

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, $model) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']), ['mail/view', 'id' => $model->id]
                    );
                }
            ],
            'options'  => [
                'width' => '30px',
            ],
        ],
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
    ],
]);
