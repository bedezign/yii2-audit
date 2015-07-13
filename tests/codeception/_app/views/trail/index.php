<?php
use tests\app\models\Post;
use yii\helpers\Html;
use yii\widgets\Menu;

$this->title = Yii::t('app', 'Trails');
$this->params['breadcrumbs'][] = $this->title;

$items = [];
$items[] = ['label' => Yii::t('app', 'create post'), 'url' => ['create']];

foreach (Post::find()->orderBy(['id' => SORT_DESC])->limit(10)->all() as $post) {
    $update = Html::a('update', ['update', 'id' => $post->id]);
    $delete = Html::a('delete', ['delete', 'id' => $post->id], [
        'data-confirm' => Yii::t('app', 'Are you sure?'),
        'data-method' => 'post',
    ]);
    $items[] = [
        'label' => '#' . $post->id . ' - ' . Html::encode($post->title) . ' - ' . $update . ' | ' . $delete,
    ];
    //$items[] = ['label' => Yii::t('app', 'update post') . ' #' . $post->id . ' - ' . $post->title, 'url' => ['update', 'id' => $post->id]];
}
?>
<div class="trail-index">

    <h1><?= Yii::t('app', 'Trails') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Create Trails') ?></h2>
            <?= Menu::widget([
                'items' => $items,
                'encodeLabels' => false,
            ]); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Trails') ?></h2>

            <p>All captured trails are available in the Audit Module pages.</p>
            <?= Html::a('view trails', ['/audit/trail'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
