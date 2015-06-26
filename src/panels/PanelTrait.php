<?php

namespace bedezign\yii2\audit\panels;

use yii\helpers\Url;

/**
 * PanelTrait
 * @package bedezign\yii2\audit\panels
 */
trait PanelTrait
{
    /**
     * @var
     */
    protected $_model;

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
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