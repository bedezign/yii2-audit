<?php

/**
 * @var $contactForm ContactForm
 */
use tests\app\models\ContactForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Mails');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mail-index">

    <h1><?= Yii::t('app', 'Mails') ?></h1>

    <div class="row">
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'Send Mail') ?></h2>

            <p>Please note that mail is sent via the FileTransport. No actual mail will be sent.</p>
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($contactForm, 'name') ?>
            <?= $form->field($contactForm, 'email') ?>
            <?= $form->field($contactForm, 'subject') ?>
            <?= $form->field($contactForm, 'body')->textArea(['rows' => 6]) ?>
            <div class="form-group">
                <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-md-6">
            <h2><?= Yii::t('app', 'View Mails') ?></h2>

            <p>All captured mails are available in the Audit Module pages.</p>
            <?= Html::a('view mails', ['/audit/mail'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>

</div>
