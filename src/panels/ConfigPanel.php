<?php

namespace bedezign\yii2\audit\panels;

use Yii;

/**
 * ConfigPanel
 * @package bedezign\yii2\audit\panels
 */
class ConfigPanel extends \yii\debug\panels\ConfigPanel
{
    use PanelHelperTrait;

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