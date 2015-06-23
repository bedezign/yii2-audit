<?php
/**
 * AssetBundle to register when you want to log javascript events as well.
 */

namespace bedezign\yii2\audit\assets;

class JSLoggingAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@bedezign/yii2/audit/assets';

    public $js = [
        'javascript/logger.js',
    ];

    public function init()
    {
        // Activate the logging as soon as we can
        $this->jsOptions['position'] = \yii\web\View::POS_HEAD;
        parent::init();
    }

    public function publish($assetManager)
    {
        $module = \bedezign\yii2\audit\Audit::current();
        if ($module && $module->entry) {
            // We can't be sure that the actual logger was loaded already, so we fallback on the window object
            // to store the associated audit url and entry id
            $url = \yii\helpers\Url::to(["/{$module->id}/javascript/log"]);
            $id = $module->getEntry()->id;
            \Yii::$app->view->registerJs("window.auditUrl = '$url'; window.auditEntry = $id;", \yii\web\View::POS_HEAD);
        }

        return parent::publish($assetManager);
    }
}