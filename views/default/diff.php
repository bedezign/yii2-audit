<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $model */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Audit Trail #{id}', ['id' => $model->id]);
if (Yii::$app->request->get('referrer') === 'trail') {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit Trail'), 'url' => ['trail']];
} else {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit Entries'), 'url' => ['index']];
    $this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit Entry #{id}', ['id' => $model->audit_id]), 'url' => ['view', 'id' => $model->audit_id]];
}
$this->params['breadcrumbs'][] = $this->title;

echo Html::tag('h1', $this->title);

echo DetailView::widget([
    'model' => $model,
    'attributes' =>
    [
        'action',
        'model',
        'model_id',
        'field',
        'stamp',
    ]
]);

echo Html::tag('h2', Yii::t('audit', 'Difference'));
echo $diff;