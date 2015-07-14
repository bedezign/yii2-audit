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
            <p>Watch your <code>console.log</code> to see the output, then check the results in the audit module.</p>
            <ul>
                <li><?= Html::a(Yii::t('app', 'create log'), 'javascript:window.jsLogger.log("this is a log")'); ?> (not logged on server with default settings)</li>
                <li><?= Html::a(Yii::t('app', 'create info'), 'javascript:window.jsLogger.info("this is some info")'); ?> (not logged on server with default settings)</li>
                <li><?= Html::a(Yii::t('app', 'create warn'), 'javascript:window.jsLogger.warn("this is a warning")'); ?></li>
                <li><?= Html::a(Yii::t('app', 'create error'), 'javascript:window.jsLogger.error("this is an error")'); ?></li>
                <li><?= Html::a(Yii::t('app', 'trigger js error'), 'javascript:test('); ?></li>
            </ul>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Javascripts') ?></h2>

            <p>All captured javascripts are available in the Audit Module pages.</p>
            <?= Html::a('view javascripts', ['/audit/javascript'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>

