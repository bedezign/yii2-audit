<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditError $model */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('audit', 'Error #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Errors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

echo Html::tag('h1', $this->title);

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        [
            'attribute' => 'entry_id',
            'value' => $model->entry_id ? Html::a($model->entry_id, ['default/view', 'id' => $model->entry_id]) : '',
            'format' => 'raw',
        ],
        'message',
        'code',
        'file',
        'line',
    ],
]);

echo Html::tag('h2', Yii::t('audit', 'Stack Trace'));

echo GridView::widget([
    'dataProvider' => new \yii\data\ArrayDataProvider([
        'allModels' => $model->trace,
    ]),
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'file',
        'line',
        [
            'header' => Yii::t('audit', 'Called'),
            'value' => function ($data) {
                return (isset($data['class']) ? $data['class'] : '[unknown]') . $data['type'] . $data['function'];
            }
        ],
        [
            'header' => Yii::t('audit', 'Args'),
            'value' => function ($data) {
                return \yii\helpers\VarDumper::dumpAsString($data['args']);
            }
        ],
    ],
]);

