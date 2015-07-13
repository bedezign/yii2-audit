<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Trails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="trail-index">

    <h1><?= Yii::t('app', 'Trails') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Create Trails') ?></h2>
            <?= Menu::widget([
                'items' => [
                    //['label' => Yii::t('app', 'undefined variable'), 'url' => ['undefined-variable']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Trails') ?></h2>

            <p>All captured trails are available in the Audit Module pages.</p>
            <?= Html::a('view trails', ['/audit/trail'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
