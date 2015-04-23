<?php

    /** @var \yii\web\View $this */
    /** @var bedezign\yii2\audit\models\AuditEntry $entry */
    use yii\helpers\Html;
    use \bedezign\yii2\audit\components\Helper;

    echo Html::tag('h2', 'Request');
    echo \yii\widgets\DetailView::widget(
    [
        'model' => $entry,
        'attributes' =>
        [
            'id',
            [
                'label' => $entry->getAttributeLabel('user_id'),
                'value' => $entry->user_id ? $entry->user_id : 'Guest',
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
    ]);

    $types =
    [
        'env'       => '$_SERVER',
        'session'   => '$_SESSION',
        'cookies'   => '$_COOKIES',
        'files'     => '$_FILES',
        'get'       => '$_GET',
        'post'      => '$_POST',
    ];

    if (count($entry->extraData)) {
        echo Html::tag('h2', 'Extra Data');
        $attributes = [];
        foreach ($entry->extraData as $data) {
            $attributes[] =
            [
                'label'     => $data->name,
                'value'     => Helper::formatValue($data->data),
                'format'    => 'raw',
            ];
        }
        echo \yii\widgets\DetailView::widget(['model' => $entry, 'attributes' => $attributes]);

    }

    if (count($entry->errors))
        foreach ($entry->errors as $error)
            echo $this->render('error', ['error' => $error]);

    foreach ($entry->data as $type => $values)
    {
        echo Html::tag('h2', $types[$type]);
        $attributes = [];
        foreach ($values as $name => $value)
            $attributes[] =
            [
                'label'     => $name,
                'value'     => Helper::formatValue($value),
                'format'    => 'raw',
            ];
        echo \yii\widgets\DetailView::widget(['model' => $entry, 'attributes' => $attributes]);
    }
