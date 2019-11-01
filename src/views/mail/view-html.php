<?php

/** @var View $this */
/** @var AuditMail $model */

use bedezign\yii2\audit\models\AuditMail;
use yii\web\View;
use yii\helpers\Html;
use bedezign\yii2\audit\Audit;

$this->title                   = Yii::t('audit', 'Mail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

if (Audit::getInstance()->hasMethod('renderEmail')) {
    Audit::getInstance()->renderEmail($this, $model);
}
elseif (class_exists('\PhpMimeMailParser\Parser')) {
    $parser = new \PhpMimeMailParser\Parser();
    $parser->setText($model->data);
    echo $parser->getMessageBody('htmlEmbedded');
} else {
    echo Html::tag('pre', $model->data) . Html::tag('br') . 'Please register a renderEmail panel function or install php-mime-mail-parser for better functionality';
}
