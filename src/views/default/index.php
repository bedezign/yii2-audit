<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('audit', 'Audit Module');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="audit-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Nav::widget([
        'items' => [
            ['label' => 'Entries', 'url' => ['entry/index']],
            ['label' => 'Trails', 'url' => ['trail/index']],
            ['label' => 'Errors', 'url' => ['error/index']],
        ],
        'options' => ['class' => 'nav-pills'],
    ]); ?>

</div>
