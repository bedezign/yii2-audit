<?php

namespace bedezign\yii2\audit\panels;

/**
 * JavascriptPanel
 * @package bedezign\yii2\audit\panels
 */
class JavascriptPanel extends \yii\debug\Panel
{
    /**
     * @return string
     */
    public function getName()
    {
        return \Yii::t('audit', 'Javascript');
    }
}