<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $entry */

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\widgets\DetailView;

use bedezign\yii2\audit\components\Helper;

$this->title = Yii::t('audit', 'Audit Entry #{entry}', ['entry' => $entry->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);

$items = [
    [
        'label' => Yii::t('audit', 'Request'),
        'content' => DetailView::widget([
            'model' => $entry,
            'attributes' =>
            [
                'id',
                [
                    'label' => $entry->getAttributeLabel('user_id'),
                    'value' => intval($entry->user_id) ? $entry->user_id : Yii::t('audit', 'Guest'),
                ],
                'ip',
                'created',
                [ 'attribute' => 'start_time', 'format' => 'datetime' ],
                [ 'attribute' => 'end_time', 'format' => 'datetime' ],
                [ 'attribute' => 'duration', 'format' => 'decimal' ],
                'referrer',
                'origin',
                'url',
                'route',
                [ 'attribute' => 'memory', 'format' => 'shortsize' ],
                [ 'attribute' => 'memory_max', 'format' => 'shortsize' ],
            ]
        ])
    ]
];

if (count($entry->extraData)) {
    $attributes = [];
    foreach ($entry->extraData as $data) {
        $attributes[] =
        [
            'label'     => $data->name,
            'value'     => Helper::formatValue($data->data),
            'format'    => 'raw',
        ];
    }

    $items[] = [
        'label' => Yii::t('audit', 'Extra Data'),
        'content' => DetailView::widget(['model' => $entry, 'attributes' => $attributes])
    ];
}


if (count($entry->errors)) {
    foreach ($entry->errors as $i => $error) {
        $items[] = [
            'label' => Yii::t('audit', 'Error #{i}', ['i' => $i + 1]),
            'content' => DetailView::widget([
                'model' => $error,
                'attributes' =>
                [
                    'message',
                    'code',
                    [
                        'label' => Yii::t('audit', 'Location'),
                        'value' => $error->file . '(' . $error->line . ')'
                    ]
                ]
            ])
        ];
    }
}

$types = [
    'env' => '$_SERVER',
    'session' => '$_SESSION',
    'cookies' => '$_COOKIES',
    'files' => '$_FILES',
    'get' => '$_GET',
    'post' => '$_POST',
];

foreach ($entry->data as $type => $values)
{
    $attributes = [];
    foreach ($values as $name => $value)
        $attributes[] =
        [
            'label'     => $name,
            'value'     => Helper::formatValue($value),
            'format'    => 'raw',
        ];

    $items[] = [
        'label' => $types[$type],
        'content' => DetailView::widget([
            'model' => $entry,
            'attributes' => $attributes,
            'template' => '<tr><th>{label}</th><td style="word-break:break-word;">{value}</td></tr>'
        ])
    ];
}

echo Tabs::widget([
    'items' => $items,
]);