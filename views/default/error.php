<?php
    /** @var \bedezign\yii2\audit\models\AuditError $error  */

    echo \yii\helpers\Html::tag('h2', Yii::t('audit', 'Error'));
    echo \yii\widgets\DetailView::widget(
    [
        'model' => $error,
        'attributes' =>
        [
            'message',
            'code',
            [
                'label' => 'Location',
                'value' => $error->file . '(' . $error->line . ')'
            ]
        ]
    ]);