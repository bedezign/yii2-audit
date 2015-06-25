<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\base\InlineAction;

/**
 * RequestPanel
 * @package bedezign\yii2\audit\panels
 */
class RequestPanel extends \yii\debug\panels\RequestPanel
{
    use PanelHelperTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return \Yii::$app->view->render('@yii/debug/views/default/panels/request/detail', ['panel' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if (Yii::$app->request instanceof \yii\console\Request) {
            return $this->saveCliRequest();
        }
        return parent::save();
    }

    /**
     * @return array
     */
    protected function saveCliRequest()
    {
        return [
            'flashes'         => $this->getFlashes(),
            'statusCode'      => 0,
            'requestHeaders'  => [],
            'responseHeaders' => [],
            'route'           => $this->getRoute(),
            'action'          => $this->getAction(),
            'actionParams'    => Yii::$app->request->params,
            'requestBody'     => [],
            'SERVER'          => empty($_SERVER) ? [] : $_SERVER,
            'GET'             => empty($_GET) ? [] : $_GET,
            'POST'            => empty($_POST) ? [] : $_POST,
            'COOKIE'          => empty($_COOKIE) ? [] : $_COOKIE,
            'FILES'           => empty($_FILES) ? [] : $_FILES,
            'SESSION'         => empty($_SESSION) ? [] : $_SESSION,
        ];
    }

    /**
     * @return null|string
     */
    protected function getAction()
    {
        if (Yii::$app->requestedAction) {
            if (Yii::$app->requestedAction instanceof InlineAction) {
                return get_class(Yii::$app->requestedAction->controller) . '::' . Yii::$app->requestedAction->actionMethod . '()';
            }
            return get_class(Yii::$app->requestedAction) . '::run()';
        }
        return null;
    }

    /**
     * @return string
     */
    protected function getRoute()
    {
        if (Yii::$app->requestedAction) {
            return Yii::$app->requestedAction->getUniqueId();
        }
        return Yii::$app->requestedRoute;
    }
}