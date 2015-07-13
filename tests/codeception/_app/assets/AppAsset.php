<?php

namespace tests\app\assets;

use yii\web\AssetBundle;

/**
 * Configuration for `backend` client script files
 * @since 4.0
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/web';

    public $css = [
        'https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.5/sandstone/bootstrap.min.css',
        'css/app.css',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'bedezign\yii2\audit\web\JSLoggingAsset',
    ];
}
