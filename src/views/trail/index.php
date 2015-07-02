<?php

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

use bedezign\yii2\audit\models\AuditTrailSearch;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Trails');
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-trail">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
            'id',
            [
                'attribute' => 'entry_id',
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return $data->entry_id ? Html::a($data->entry_id, ['entry/view', 'id' => $data->entry_id]) : '';
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_id',
                'label' => Yii::t('audit', 'User ID'),
                'class' => 'yii\grid\DataColumn',
                'value' => function ($data) {
                    return Audit::getInstance()->getUserIdentifier($data->user_id);
                },
                'format' => 'raw',
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
            [
                'attribute' => 'created',
                'options' => ['width' => '150px'],
            ],
        ],
    ]); ?>
</div>
