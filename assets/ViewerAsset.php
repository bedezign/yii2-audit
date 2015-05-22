<?php

namespace bedezign\yii2\audit\assets;

use yii\web\AssetBundle;

class ViewerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bedezign/yii2/audit/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/auditing.css',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}