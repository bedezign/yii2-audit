<?php

$config = [
    'id' => 'yii2-audit-web',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'bedezign\yii2\audit\Bootstrap'
    ],
    'extensions' => require(VENDOR_DIR . '/yiisoft/extensions.php'),
    'aliases' => [
        '@bedezign/yii2/audit' => realpath(__DIR__ . '/../../../../src'),
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower',
        '@tests' => realpath(__DIR__ . '/../../..'),
        '@tests/codeception/config' => '@tests/codeception/_config'
    ],
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'accessUsers' => null,
            'accessRoles' => null,
        ],
    ],
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'cache' => null,
        'db' => require __DIR__ . '/db.php',
        'log' => null,
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
];

if (defined('YII_APP_BASE_PATH')) {
    $config = Codeception\Configuration::mergeConfigs(
        $config,
        require YII_APP_BASE_PATH . '/tests/codeception/config/config.php'
    );
}

return $config;
