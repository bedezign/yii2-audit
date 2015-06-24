<?php

namespace bedezign\yii2\audit\components\web;

use bedezign\yii2\audit\assets\ViewerAsset;
use bedezign\yii2\audit\Audit;
use Yii;
use yii\web\View;

/**
 * Base Controller
 * @package bedezign\yii2\audit\components\web
 *
 * @property Audit $module
 * @property View  $view
 */
class Controller extends \yii\web\Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => $this->module->getAccessControlFilter()
        ];
    }

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        ViewerAsset::register($this->view);
        return parent::beforeAction($action);
    }

}
