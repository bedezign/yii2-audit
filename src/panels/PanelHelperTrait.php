<?php

namespace bedezign\yii2\audit\panels;

use yii\helpers\Url;

trait PanelHelperTrait
{
    public function getUrl()
    {
        return Url::toRoute(['/' . $this->module->id . '/entry/view',
            'panel' => $this->id,
            'id'    => $this->tag,
        ]);
    }
}