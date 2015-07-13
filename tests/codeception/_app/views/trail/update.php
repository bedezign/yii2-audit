<?php

use yii\helpers\Html;
use cornernote\returnurl\ReturnUrl;

/**
 * @var yii\web\View $this
 * @var tests\app\models\Post $post
 */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trails'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="post-update">

    <?= $this->render('_form', [
        'post' => $post,
    ]); ?>

</div>
