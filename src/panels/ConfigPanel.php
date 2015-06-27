<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\PanelTrait;
use Yii;

/**
 * ConfigPanel
 * @package bedezign\yii2\audit\panels
 */
class ConfigPanel extends \yii\debug\panels\ConfigPanel
{
    use PanelTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/config/detail', [
            'panel' => $this,
        ]);
    }
}