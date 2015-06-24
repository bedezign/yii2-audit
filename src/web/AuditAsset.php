<?php

namespace bedezign\yii2\audit\web;

use yii\web\AssetBundle;

/**
 * AuditAsset
 * @package bedezign\yii2\audit\assets
 */
class AuditAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bedezign/yii2/audit/web/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/audit.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
    ];
}