<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Errors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="error-index">

    <h1><?= Yii::t('app', 'Errors') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Create Errors') ?></h2>
            <?= Menu::widget([
                'items' => [
                    ['label' => Yii::t('app', 'undefined variable'), 'url' => ['undefined-variable']],
                    ['label' => Yii::t('app', 'undefined method'), 'url' => ['undefined-function']],
                    ['label' => Yii::t('app', 'method on non object'), 'url' => ['method-non-object']],
                    ['label' => Yii::t('app', '404 page not found'), 'url' => ['broken-link']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Errors') ?></h2>

            <p>All captured errors are available in the Audit Module pages.</p>
            <?= Html::a('view errors', ['/audit/error'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
