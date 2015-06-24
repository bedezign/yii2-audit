<?php

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;
use yii\grid\GridView;

use bedezign\yii2\audit\models\AuditEntrySearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
                    return Audit::current()->getUserIdentifier($data->user_id);
                }
            ],
            'request_method',
            'ajax',
            'created',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'route',
                'filter' => AuditEntrySearch::routeFilter(),
                'format' => 'html',
                'value' => function ($data) {
                    return HTML::tag('span', '', [
                        'title' => $data->url,
                        'class' => 'glyphicon glyphicon-link'
                    ]) . ' ' . $data->route;
                },
            ],
            ['attribute' => 'duration', 'format' => 'decimal'],
            ['attribute' => 'memory_max', 'format' => 'shortsize'],
            ['attribute' => 'errors', 'value' => function ($data) {
                return is_array($data->linkedErrors) ? count($data->linkedErrors) : 0;
            }],
            ['attribute' => 'javascript', 'value' => function ($data) {
                return is_array($data->javascript) ? count($data->javascript) : 0;
            }],
        ],
    ]); ?>
</div>
