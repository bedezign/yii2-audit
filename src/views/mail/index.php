<?php

use bedezign\yii2\audit\models\AuditMailSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */
/* @var $searchModel AuditMailSearch */

$this->title = Yii::t('audit', 'Mails');
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-mail">

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
                'attribute' => 'successful',
                'options' => [
                    'width' => '80px',
                ],
            ],
            'to',
            'from',
            'reply',
            'cc',
            'bcc',
            'subject',
            [
                'attribute' => 'created',
                'options' => ['width' => '150px'],
            ],
        ],
    ]); ?>
</div>
