<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

use bedezign\yii2\audit\models\AuditTrailSearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Trail');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-trail">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'entry_id',
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return $data->entry_id ? Html::a($data->entry_id, ['default/view', 'id' => $data->entry_id]) : '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('audit', 'User ID'),
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return $data->user_id ?: Yii::t('audit', 'Guest');
                }
            ],
            [
                'attribute' => 'action',
                'filter' => AuditTrailSearch::actionFilter(),
            ],
            'model',
            'model_id',
            'field',
            [
                'label' => Yii::t('audit', 'Diff'),
                'value' => function ($model) {
                    return $model->getDiffHtml();
                },
                'format' => 'raw',
            ],
            'stamp',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a(
                            Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']),
                            ['diff', 'id' => $model->id, 'referrer' => 'trail'],
                            [
                                'class' => '',
                                'data' => [
                                    'toggle' => 'modal',
                                ]
                            ]
                        );
                    }
                ]
            ]
        ],
    ]); ?>
</div>
