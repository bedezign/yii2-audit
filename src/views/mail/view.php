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
        [
            'label' => Yii::t('audit', 'Download'),
            'value' => Html::a(Yii::t('audit', 'Download eml file'), ['mail/download', 'id' => $model->id]),
            'format' => 'raw',
        ],
        'created',
    ],
]);

echo Html::tag('h2', Yii::t('audit', 'Text'));
echo '<div class="well">';
echo Yii::$app->formatter->asNtext($model->text);
echo '</div>';

echo Html::tag('h2', Yii::t('audit', 'HTML'));
echo '<div class="well">';
echo Yii::$app->formatter->asHtml($model->html);
echo '</div>';

//echo Html::tag('h2', Yii::t('audit', 'Data'));
//echo '<div class="well">';
//echo Yii::$app->formatter->asNtext(Helper::uncompress($model->data));
//echo '</div>';