<?php

namespace bedezign\yii2\audit\panels;

class ErrorPanel extends \yii\debug\Panel
{
    public function getName()
    {
        return \Yii::t('audit', 'Error');
    }
}