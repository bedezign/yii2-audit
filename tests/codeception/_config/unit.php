<?php

/**
 * Application configuration for unit tests
 */
return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../_app/config/console.php'),
    [
        'class' => 'yii\console\Application',
        'modules' => [
            'audit' => [
                'class' => 'bedezign\yii2\audit\Audit',
                'accessUsers' => null,
                'accessRoles' => null,
            ],
        ],
    ]
);