<?php

use AspectMock\Kernel;

require __DIR__ . '/_init.php';

// without following line test on travis fails
require_once VENDOR_DIR . '/yiisoft/yii2/base/ErrorException.php';

$kernel = Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [__DIR__ . '/../../src', VENDOR_DIR],
    'excludePaths' => [__DIR__, __DIR__ . '/../../build'],
    'cacheDir' => '/tmp/yii2-audit/aop',
]);
$kernel->loadFile(VENDOR_DIR . '/yiisoft/yii2/Yii.php');

$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;
$_SERVER['SERVER_NAME'] = 'localhost';

Yii::setAlias('@tests', dirname(__DIR__));
Yii::setAlias('@bedezign/yii2/audit', realpath(__DIR__ . '../../src'));
