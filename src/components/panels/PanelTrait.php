<?php

namespace bedezign\yii2\audit\components\panels;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models\AuditData;
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
 * @property AuditEntry $model
 * @method string getName()
 */
trait PanelTrait
{
    /**
     * @var int Maximum age (in days) of the data before it is cleaned
     */
    public $maxAge = null;

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
     * @return array|bool
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
    public function getUrl($additionalParams = NULL)
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

    /**
     * @param int|null $maxAge
     * @return int
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditData::deleteAll('type = :type AND created <= :created', [
            ':type' => $this->id,
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]);
    }

}
