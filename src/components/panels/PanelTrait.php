<?php

namespace bedezign\yii2\audit\components\panels;

use bedezign\yii2\audit\Audit;
use yii\helpers\Url;
use yii\web\View;

/**
 * PanelTrait
 * @package bedezign\yii2\audit\panels
 *
 * @property Audit $module
 * @property array|mixed $data
 * @property string $id
 * @property string $tag
 * @method string getName()
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

    /**
     * @param View $view
     */
    public function registerAssets($view)
    {

    }
}