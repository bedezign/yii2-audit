<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\models;

class JavascriptController extends \yii\web\Controller
{
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionLog()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = \yii\helpers\Json::decode(\Yii::$app->request->post('data'));
        $entry = null;
        if (isset($data['auditEntry']))
            $entry = models\AuditEntry::findOne($data['auditEntry']);
        else
            return ['result' => 'error', 'message' => 'No audit entry to attach to'];

        // Convert data into the loggable object
        $javascript = new models\AuditJavascript();
        $map = [
            'auditEntry' => 'entry_id',
            'message'    => 'message',
            'type'       => 'type',
            'file'       => 'origin',
            'line'       => function($value) use ($javascript) { $javascript->origin .= ':' . $value; },
            'col'        => function($value) use ($javascript) { $javascript->origin .= ':' . $value; },
            'data'       => function($value) use ($javascript) { if (count($value)) $javascript->data = $value; },
        ];

        foreach ($map as $key => $target)
            if (isset($data[$key])) {
                if (is_callable($target)) $target($data[$key]);
                else $javascript->$target = $data[$key];
            }

        if ($javascript->save())
            return ['result' => 'ok'];

        return ['result' => 'error', 'errors' => $javascript->getErrors()];
    }
}