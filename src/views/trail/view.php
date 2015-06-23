<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditTrail $model */

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Trail #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Trails'), 'url' => ['trail/index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

echo Html::tag('h1', $this->title);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'label' => $model->getAttributeLabel('user_id'),
            'value' => Audit::current()->getUserIdentifier($model->user_id),
        ],
        'action',
        'model',
        'model_id',
        'field',
        'stamp',
    ],
]);

echo Html::tag('h2', Yii::t('audit', 'Difference'));
echo $model->getDiffHtml();
