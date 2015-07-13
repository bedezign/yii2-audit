<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;
?>

<div class="jumbotron">
    <div class="container">
        <h1><?= Yii::$app->name; ?></h1>

        <p>
            <strong>NOTE: This site will track your IP address and other information on all pages apart from the home page</strong>.
        </p>

        <p>If you visit any other page on this site your information will be displayed in the public reports. If you do not wish for your information to be tracked then do not proceed.</p>

        <hr>
        <p>
            <a class="btn btn-primary btn-lg" href="<?= Url::to(['/audit']); ?>" role="button">View Audit Data</a>
            <a class="btn btn-info btn-lg" href="https://bedezign.github.io/yii2-audit/" role="button">Create Audit Data</a>
            <a class="btn btn-success btn-lg" href="https://bedezign.github.io/yii2-audit/" role="button">Project Homepage</a>
        </p>
    </div>
</div>
