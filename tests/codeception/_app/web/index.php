<?php

require __DIR__ . '/../../_init.php';
require(VENDOR_DIR . '/yiisoft/yii2/Yii.php');

Yii::setAlias('@tests', __DIR__ . '/../../..');
Yii::setAlias('@tests/app', '@tests/codeception/_app');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);
$application->run();
