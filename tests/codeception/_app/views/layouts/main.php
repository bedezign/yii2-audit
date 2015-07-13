<?php
/* @var $this \yii\web\View */
/* @var $content string */

use tests\app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-default navbar-fixed-top navbar-fluid'],
        //'innerContainerOptions' => ['class' => 'container-fluid'],
    ]);
    echo Nav::widget([
        'items' => [
            ['label' => Yii::t('audit', 'View Audit Data'), 'url' => ['/audit']],
            ['label' => Yii::t('audit', 'Create Audit Data'), 'url' => ['/data']],
        ],
        'options' => ['class' => 'navbar-nav'],
    ]);
    echo Nav::widget([
        'items' => [
            ['label' => 'Project Homepage', 'url' => 'https://bedezign.github.io/yii2-audit/'],
        ],
        'options' => ['class' => 'navbar-nav navbar-right'],
    ]);
    NavBar::end();

    if (isset($this->params['jumbotron'])) {
        echo $this->render($this->params['jumbotron']);
    }
    ?>

    <div class="container">
        <?php if (isset($this->params['breadcrumbs'])) { ?>
            <div class="breadcrumbs">
                <?= Breadcrumbs::widget([
                    'links' => $this->params['breadcrumbs'],
                ]) ?>
            </div>
        <?php } ?>
        <?= $content ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>