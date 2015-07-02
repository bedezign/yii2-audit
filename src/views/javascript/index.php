<?php

use bedezign\yii2\audit\models\AuditJavascriptSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $searchModel AuditJavascriptSearch */

$this->title = Yii::t('audit', 'Javascripts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-javascript">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            [
                'attribute' => 'id',
                'options' => [
                    'width' => '80px',
                ],
            ],
            [
                'attribute' => 'entry_id',
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return $data->entry_id ? Html::a($data->entry_id, ['entry/view', 'id' => $data->entry_id]) : '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'type',
                'options' => [
                    'width' => '80px',
                ],
            ],
            [
                'attribute' => 'origin',
                'options' => [
                    'width' => '80px',
                ],
            ],
            'message',
            [
                'attribute' => 'created',
                'options' => ['width' => '150px'],
            ],
        ],
    ]); ?>
</div>
