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

    <div class="row">
        <div class="col-md-4">
            <h2><?= Yii::t('app', 'Update Post'); ?></h2>
            <?= $this->render('_form', [
                'post' => $post,
            ]); ?>
        </div>
        <div class="col-md-8">
            <h2><?= Yii::t('app', 'Post History'); ?></h2>
            <?= $this->render('@bedezign/yii2/audit/views/_audit_trails', [
                'query' => $post->getAuditTrails(),
                'columns' => ['entry_id', 'action', 'diff', 'created'],
            ]); ?>
        </div>
    </div>

</div>
