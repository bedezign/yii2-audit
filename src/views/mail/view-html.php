<?php

/** @var View $this */
/** @var AuditMail $model */

use bedezign\yii2\audit\models\AuditMail;
use yii\web\View;

$this->title = Yii::t('audit', 'Mail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;


echo Yii::$app->formatter->asHtml($model->html);