<?php

namespace bedezign\yii2\audit\components\panels;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditEntry;
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
     * @var AuditEntry
     */
    protected $_model;

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->data) . ')</small>';
    }

    /**
     * @return string|bool
     */
    public function getIndexUrl()
    {
        return false;
    }

    /**
     * @return string|bool
     */
    public function getChart()
    {
        return false;
    }

    /**
     * @param AuditEntry $model
     */
    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Returns if the panel is available for the specified entry.
     * If not it will not be shown in the viewer.
     *
     * @param AuditEntry $entry
     * @return bool
     */
    public function hasEntryData($entry)
    {
        return false;
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