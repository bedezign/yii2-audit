<?php

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') ? (getenv('YII_DEBUG') == 'false' ? false : getenv('YII_DEBUG')) : true);
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ? getenv('YII_ENV') : 'test');
defined('VENDOR_DIR') or define('VENDOR_DIR', __DIR__ . '/../../../../vendor');

require(VENDOR_DIR . '/autoload.php');
require(VENDOR_DIR . '/yiisoft/yii2/Yii.php');

Yii::setAlias('@tests', __DIR__ . '/../../..');
Yii::setAlias('@tests/app', '@tests/codeception/_app');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);
$application->run();
