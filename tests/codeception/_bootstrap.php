<?php

require __DIR__ . '/_init.php';

// without following line test on travis fails
//require_once VENDOR_DIR . '/yiisoft/yii2/base/ErrorException.php';
//require_once __DIR__ . '/../../src/components/Helper.php';

require_once(VENDOR_DIR . '/yiisoft/yii2/Yii.php');

$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = YII_TEST_ENTRY_URL;
$_SERVER['SERVER_NAME'] = 'localhost';

Yii::setAlias('@tests', dirname(__DIR__));
Yii::setAlias('@tests/codeception/config', '@tests/codeception/_config');
Yii::setAlias('@yii/debug', VENDOR_DIR . '/yiisoft/yii2-debug'); // needed for hhvm
