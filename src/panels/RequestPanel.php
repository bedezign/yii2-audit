<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\base\InlineAction;

class RequestPanel extends \yii\debug\panels\RequestPanel
{
    public function getDetail()
    {
        return \Yii::$app->view->render('@yii/debug/views/default/panels/request/detail', ['panel' => $this]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        // handle CLI requests
        $request = Yii::$app->request;
        if ($request instanceof \yii\console\Request) {
            if (Yii::$app->requestedAction) {
                if (Yii::$app->requestedAction instanceof InlineAction) {
                    $action = get_class(Yii::$app->requestedAction->controller) . '::' . Yii::$app->requestedAction->actionMethod . '()';
                } else {
                    $action = get_class(Yii::$app->requestedAction) . '::run()';
                }
            } else {
                $action = null;
            }
            return [
                'flashes' => $this->getFlashes(),
                'statusCode' => 0,
                'requestHeaders' => [],
                'responseHeaders' => [],
                'route' => Yii::$app->requestedAction ? Yii::$app->requestedAction->getUniqueId() : Yii::$app->requestedRoute,
                'action' => $action,
                'actionParams' => $request->params,
                'requestBody' => [],
                'SERVER' => empty($_SERVER) ? [] : $_SERVER,
                'GET' => empty($_GET) ? [] : $_GET,
                'POST' => empty($_POST) ? [] : $_POST,
                'COOKIE' => empty($_COOKIE) ? [] : $_COOKIE,
                'FILES' => empty($_FILES) ? [] : $_FILES,
                'SESSION' => empty($_SESSION) ? [] : $_SESSION,
            ];
        }
        // handle other requests
        return parent::save();
    }
}