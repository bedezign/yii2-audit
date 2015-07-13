<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use \dmstr\bootstrap\Tabs;
use cornernote\returnurl\ReturnUrl;

/**
 * @var yii\web\View $this
 * @var tests\app\models\Post $post
 * @var yii\bootstrap\ActiveForm $form
 */

?>

<div class="post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($post, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($post, 'body')->textarea(['rows' => 6]) ?>

    <?= Html::submitButton('<span class="fa fa-check"></span> ' . ($post->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save')), [
        'id' => 'save-' . $post->formName(),
        'class' => 'btn btn-success'
    ]); ?>

    <?php ActiveForm::end(); ?>

</div>
