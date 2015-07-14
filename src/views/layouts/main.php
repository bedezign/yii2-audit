<?php
/* @var $this \yii\web\View */
/* @var $content string */

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\web\JSLoggingAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\debug\DebugAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

DebugAsset::register($this);
JSLoggingAsset::register($this)
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->registerCss('body{padding-top: 60px;} .navbar-brand { background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABsElEQVRIS82UwU3DQBBF5+8lR5IO0gFQAaGD0EGoAF+yu0dy804OMRUAFUAH0AEpIXQQbsnFi75kR8Y4wREJwpJlybveN//PH0OOfOHI58v/AYzH4zNjzCyEcLmP6tYKvPeDGONLCAHOuRhjXAJ4BjBJ03SxDboBsEIA96p6Xt3snJsDSPiuBFhrXwGcicgJ3wO4TtP0oQmyAVQrrAFY7cQY81oCynXv/SjGmBEUY7xS1ec65FcAHlb05o2WqWrvG8A5t4wxjowxfJYes6p+CGFY+N2ooDzMOcf9N00qyoZ9OYAe82NVHbQBlPbSSlW9rar4E0AGgBKZkiSEkFhrh8aYLpNB+fX1us/W2gTArClNO+eA0vM8vzDGvG+LYZIk3U6n88aerdfrXpZlyy8W7ZrK4mP241REqO6uur9YfxIRDuGjqo62xnQbqIghIcz6XETKrHdFZASAT9rLFF5Op1Pu2VytfhXe+36e5w8ALmqFfHDQAAypsgnSClCZ3D69Lu7FarWa0/OqlXXIXoC2/apCDgYgvEnJQQFNkIMDKhD+vhdHAewd013N/Wnt6Ao+AURRX/2e7CxYAAAAAElFTkSuQmCC") no-repeat 10px; padding-left: 40px; }'); ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => Yii::t('audit', 'Audit'),
    'brandUrl' => ['default/index'],
    'options' => ['class' => 'navbar-default navbar-fixed-top navbar-fluid'],
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);

$items = [
    ['label' => Yii::t('audit', 'Entries'), 'url' => ['entry/index']],
];
foreach (Audit::getInstance()->panels as $panel) {
    /** @var Panel $panel */
    $indexUrl = $panel->getIndexUrl();
    if (!$indexUrl) {
        continue;
    }
    $items[] = ['label' => $panel->getName(), 'url' => $indexUrl];
}

echo Nav::widget([
    'items' => $items,
    'options' => ['class' => 'navbar-nav'],
]);
echo Nav::widget([
    'items' => [
        ['label' => Yii::$app->name, 'url' => Yii::$app->getHomeUrl()],
    ],
    'options' => ['class' => 'navbar-nav navbar-right'],
]);
NavBar::end();
?>

<div class="container-fluid">
    <?php if (isset($this->params['breadcrumbs'])) { ?>
        <div class="breadcrumbs">
            <?= Breadcrumbs::widget([
                'links' => $this->params['breadcrumbs'],
            ]) ?>
        </div>
    <?php } ?>

    <?= $content ?>

    <footer class="text-center">
        <hr>
        <?= date('Y-m-d H:i:s'); ?>
        <br>
        <?= $this->render('@bedezign/yii2/audit/views/_audit_entry_id', ['style' => '']); ?>
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
