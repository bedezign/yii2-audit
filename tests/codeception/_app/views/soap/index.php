<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'SOAP');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="soap-index">

    <h1><?= Yii::t('app', 'SOAP') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Perform SOAP requests') ?></h2>
            <?= Menu::widget([
                'items' => [
                    ['label' => Yii::t('app', 'City Info By Zip'), 'url' => ['zip']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View SOAP requests') ?></h2>

            <p>All captured SOAP requests are available under their matching entry.</p>
            <?= Html::a('view entries', ['/audit/entry'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
