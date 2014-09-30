<?php

    /** @var bedezign\yii2\audit\models\AuditEntry $entry */
    use yii\helpers\Html;

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

    foreach ($entry->data as $type => $values)
    {
        echo Html::tag('h2', $types[$type]);
        $attributes = [];
        foreach ($values as $name => $value)
            $attributes[] =
            [
                'label'     => $name,
                'value'     => htmlspecialchars(\yii\helpers\VarDumper::dumpAsString($value), ENT_QUOTES|ENT_SUBSTITUTE, \Yii::$app->charset, true),
                'format'    => 'raw',
            ];
        echo \yii\widgets\DetailView::widget(['model' => (object)$values, 'attributes' => $attributes]);
    }

