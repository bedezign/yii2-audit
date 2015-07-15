<?php

return [
    'id' => 'yii2-audit-console',
    'name' => 'Yii2 Audit Demo',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'tests\app\commands',
    'bootstrap' => [
        'bedezign\yii2\audit\Bootstrap',
        'audit',
    ],
    'extensions' => require(VENDOR_DIR . '/yiisoft/extensions.php'),
    'aliases' => [
        '@vendor' => VENDOR_DIR,
        '@bedezign/yii2/audit' => realpath(__DIR__ . '../../../../src'),
    ],
    'params' => [
        'supportEmail' => 'errors@example.com',
    ],
    'controllerMap' => [
        'audit' => 'bedezign\yii2\audit\commands\AuditController',
    ],
    'components' => [
        'cache' => [
            'class' => YII_ENV == 'heroku' ? 'yii\caching\FileCache' : 'yii\caching\DummyCache',
        ],
        'db' => require __DIR__ . '/db.php',
        'errorHandler' => [
            'class' => '\bedezign\yii2\audit\components\console\ErrorHandler',
        ],
        'log' => [
            'traceLevel' => getenv('YII_TRACE_LEVEL'),
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => YII_ENV == 'heroku' ? 'php://stderr' : '@app/runtime/logs/console.log',
                    'dirMode' => 0777
                ],
            ],
        ],
        'urlManager' => [
            'scriptUrl' => 'http://example.com/',
        ],
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'compressData' => YII_ENV == 'heroku' ? true : false,
            'panelsMerge' => [
                'app/views' => ['class' => 'tests\app\panels\ViewsPanel'],
            ],
            'ignoreActions' => [
                'migrate/*',
            ],
        ],
    ],
];