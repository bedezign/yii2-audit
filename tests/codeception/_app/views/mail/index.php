<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Mails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-index">

    <h1><?= Yii::t('app', 'Mails') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Create Mails') ?></h2>
            <?= Menu::widget([
                'items' => [
                    //['label' => Yii::t('app', 'undefined variable'), 'url' => ['undefined-variable']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Mails') ?></h2>

            <p>All captured mails are available in the Audit Module pages.</p>
            <?= Html::a('view mails', ['/audit/mail'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
