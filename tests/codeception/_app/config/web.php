<?php

$config = [
    'id' => 'yii2-audit-web',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'bedezign\yii2\audit\Bootstrap'
    ],
    'extensions' => require(VENDOR_DIR . '/yiisoft/extensions.php'),
    'aliases' => [
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower',
        '@bedezign/yii2/audit' => realpath(__DIR__ . '../../../../src'),
    ],
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'cache' => null,
        'db' => require __DIR__ . '/db.php',
        'errorHandler' => [
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => getenv('YII_TRACE_LEVEL'),
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                    'logFile' => '@app/runtime/logs/web.log',
                    'dirMode' => 0777
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessIps' => null,
            'accessUsers' => null,
            'accessRoles' => null,
            'compressData' => false,
            'panels' => [
                'request',
                'error',
                'db',
                'log',
                'mail',
                'profiling',
                'app/views' => 'app\panels\ViewsPanel',
                //'views' => ['class' => 'app\panels\ViewsPanel'],
                // 'asset',
                // 'config',
            ],
        ],
    ],
];

if (defined('YII_APP_BASE_PATH')) {
    $config = Codeception\Configuration::mergeConfigs(
        $config,
        require YII_APP_BASE_PATH . '/tests/codeception/config/config.php'
    );
}

return $config;
