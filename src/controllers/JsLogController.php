<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\models;
use Yii;
use yii\helpers\Json;
use yii\web\Response;

/**
 * JsLogController
 * @package bedezign\yii2\audit\controllers
 */
class JsLogController extends \yii\web\Controller
{
    /**
     * @param \yii\base\Action $action
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $data = Json::decode(Yii::$app->request->post('data'));
        if (!isset($data['auditEntry'])) {
            $entry = Audit::getInstance()->getEntry(true);
            $data['auditEntry'] = $entry->id;
        }

        // Convert data into the loggable object
        $javascript = new models\AuditJavascript();
        $map = [
            'auditEntry' => 'entry_id',
            'message'    => 'message',
            'type'       => 'type',
            'file'       => 'origin',
            'line'       => function ($value) use ($javascript) {
                $javascript->origin .= ':' . $value;
            },
            'col'        => function ($value) use ($javascript) {
                $javascript->origin .= ':' . $value;
            },
            'data'       => function ($value) use ($javascript) {
                if (count($value)) $javascript->data = $value;
            },
        ];

        foreach ($map as $key => $target)
            if (isset($data[$key])) {
                if (is_callable($target)) $target($data[$key]);
                else $javascript->$target = $data[$key];
            }

        if (!$javascript->type)
            $javascript->type = 'unknown';

        if ($javascript->save())
            return ['result' => 'ok', 'entry' => $data['auditEntry']];

        return ['result' => 'error', 'errors' => $javascript->getErrors()];
    }
}