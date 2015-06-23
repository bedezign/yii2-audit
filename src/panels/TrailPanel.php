<?php

namespace bedezign\yii2\audit\panels;

class TrailPanel extends \yii\debug\Panel
{
    public function getName()
    {
        return \Yii::t('audit', 'Trail');
    }

}