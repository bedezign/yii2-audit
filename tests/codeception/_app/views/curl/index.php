<?php
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'cURL');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="curl-index">

    <h1><?= Yii::t('app', 'cURL') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Perform cURL requests') ?></h2>
            <?= Menu::widget([
                'items' => [
                    ['label' => Yii::t('app', 'google.com (HTML)'), 'url' => ['google']],
                    ['label' => Yii::t('app', 'Quote of the Day (JSON)'), 'url' => ['quote-of-the-day']],
                    ['label' => Yii::t('app', 'Quote of the Day (XML)'), 'url' => ['quote-of-the-day-xml']],
                ],
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View cURL requests') ?></h2>

            <p>All captured curl requests are available in their matching entry.</p>
            <?= Html::a('view entries', ['/audit/entry'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
