<?php

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') ? (getenv('YII_DEBUG') == 'false' ? false : getenv('YII_DEBUG')) : true);
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ? getenv('YII_ENV') : 'test');
defined('YII_TEST_ENTRY_URL') or define('YII_TEST_ENTRY_URL', '/index.php');
defined('YII_TEST_ENTRY_FILE') or define('YII_TEST_ENTRY_FILE', __DIR__ . '/_app/web/index.php');
defined('VENDOR_DIR') or define('VENDOR_DIR', __DIR__ . '/../../vendor');

require_once(VENDOR_DIR . '/autoload.php');