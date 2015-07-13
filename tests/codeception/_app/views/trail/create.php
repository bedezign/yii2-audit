<?php

use yii\helpers\Html;
use yii\helpers\Url;
use cornernote\returnurl\ReturnUrl;

/**
 * @var yii\web\View $this
 * @var \tests\app\models\Post $post
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <?= $this->render('_form', [
        'post' => $post,
    ]); ?>

</div>
