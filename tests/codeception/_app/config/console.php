<?php

return [
    'id' => 'yii2-audit-console',
    'basePath' => dirname(__DIR__),
    'components' => [
        'log' => null,
        'cache' => null,
        'db' => require __DIR__ . '/db.php',
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessRoles' => ['*'],
        ],
    ],
];