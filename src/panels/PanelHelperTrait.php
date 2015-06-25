<?php

namespace bedezign\yii2\audit\panels;

use yii\helpers\Url;

/**
 * Class PanelHelperTrait
 * @package bedezign\yii2\audit\panels
 */
trait PanelHelperTrait
{
    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return parent::getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return Url::toRoute(['/' . $this->module->id . '/entry/view',
            'panel' => $this->id,
            'id' => $this->tag,
        ]);
    }
}