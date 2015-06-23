<?php

namespace bedezign\yii2\audit\panels;

class JavascriptPanel extends \yii\debug\Panel
{
    public function getName()
    {
        return \Yii::t('audit', 'Javascript');
    }
}