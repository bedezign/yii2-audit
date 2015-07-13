<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Javascripts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="javascript-index">

    <h1><?= Yii::t('app', 'Javascripts') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Create Javascripts') ?></h2>
            <?= Menu::widget([
                'items' => [
                    //['label' => Yii::t('app', 'undefined variable'), 'url' => ['undefined-variable']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Javascripts') ?></h2>

            <p>All captured javascripts are available in the Audit Module pages.</p>
            <?= Html::a('view javascripts', ['/audit/javascript'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
