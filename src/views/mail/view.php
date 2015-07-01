<?php

/** @var View $this */
/** @var AuditMail $model */

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\models\AuditMail;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Mail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Mails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

echo Html::tag('h1', $this->title);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'entry_id',
            'value' => $model->entry_id ? Html::a($model->entry_id, ['entry/view', 'id' => $model->entry_id]) : '',
            'format' => 'raw',
        ],
        'successful',
        'to',
        'from',
        'reply',
        'cc',
        'bcc',
        'subject',
    ],
]);

echo Html::tag('h2', Yii::t('audit', 'Headers'));
echo '<div class="well">';
echo Helper::unserialize($model->headers);
echo '</div>';

echo Html::tag('h2', Yii::t('audit', 'Body'));
echo '<div class="well">';
echo Helper::unserialize($model->body);
echo '</div>';

echo Html::tag('h2', Yii::t('audit', 'Data'));
echo '<div class="well">';
echo Helper::unserialize($model->data);
echo '</div>';