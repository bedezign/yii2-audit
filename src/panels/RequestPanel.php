<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\DataStoragePanelTrait;
use bedezign\yii2\audit\models\AuditData;
use Yii;
use yii\base\InlineAction;

/**
 * RequestPanel
 * @package bedezign\yii2\audit\panels
 */
class RequestPanel extends \yii\debug\panels\RequestPanel
{
    use DataStoragePanelTrait;

    /**
     * @inheritdoc
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
            return \yii\helpers\Inflector::camel2id(Yii::$app->requestedAction->getUniqueId());
        }
        return Yii::$app->requestedRoute;
    }

    /**
     * @inheritdoc
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditData::deleteAll('type = :type AND created <= :created', [
            ':type' => 'audit/request',
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]) !== false;
    }

}