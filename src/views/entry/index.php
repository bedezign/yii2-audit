<?php

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;
use yii\grid\GridView;
use bedezign\yii2\audit\models\AuditEntrySearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel AuditEntrySearch */

$this->title = Yii::t('audit', 'Entries');
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-entry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            'id',
            [
                'attribute' => 'user_id',
                'label' => Yii::t('audit', 'User'),
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return Audit::getInstance()->getUserIdentifier($data->user_id);
                },
                'format' => 'raw',
            ],
            'ip',
            [
                'filter' => AuditEntrySearch::methodFilter(),
                'attribute' => 'request_method',
            ],
            [
                'filter' => [1 => \Yii::t('audit', 'Yes'), 0 => \Yii::t('audit', 'No')],
                'attribute' => 'ajax',
                'value' => function($data) {
                    return $data->ajax ? Yii::t('audit', 'Yes') : Yii::t('audit', 'No');
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'route',
                'filter' => AuditEntrySearch::routeFilter(),
                'format' => 'html',
                'value' => function ($data) {
                    return HTML::tag('span', '', [
                        'title' => \yii\helpers\Url::to([$data->route]),
                        'class' => 'glyphicon glyphicon-link'
                    ]) . ' ' . $data->route;
                },
            ],
            [
                'attribute' => 'duration',
                'format' => 'decimal',
                'contentOptions' => ['class' => 'text-right', 'width' => '100px'],
            ],
            [
                'attribute' => 'memory_max',
                'format' => 'shortsize',
                'contentOptions' => ['class' => 'text-right', 'width' => '100px'],
            ],
            [
                'attribute' => 'trails',
                'value' => function ($data) {
                    return $data->getTrails()->count();
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'mails',
                'value' => function ($data) {
                    return $data->getMails()->count();
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'javascripts',
                'value' => function ($data) {
                    return $data->getJavascripts()->count();
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'errors',
                'value' => function ($data) {
                    return $data->getLinkedErrors()->count();
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'created',
                'options' => ['width' => '150px'],
            ],
        ],
    ]); ?>
</div>
