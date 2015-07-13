<?php
use yii\helpers\Html;

$this->params['jumbotron'] = '/site/_index-jumbotron';
?>
<div class="site-index">

    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Trails
                        <span class="pull-right">
                            <?= Html::a('create', ['/trail/index'], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a('view', ['/audit/trail/index'], ['class' => 'btn btn-default btn-xs']) ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <p>Whenever a model is saved in your application the changes will be logged to the
                        <code>audit_trail</code> table.</p>

                    <p>To activate this feature simply add the <code>AuditTrailBehavior</code> to your models
                        <code>behaviors()</code> method.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Mails
                        <span class="pull-right">
                            <?= Html::a('create', ['/mail/index'], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a('view', ['/audit/mail/index'], ['class' => 'btn btn-default btn-xs']) ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <p>All mail sent from your application will be logged to the <code>audit_mail</code> table.</p>

                    <p>This feature is automatically activated when you enable the <code>MailPanel</code>.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Javascripts
                        <span class="pull-right">
                            <?= Html::a('create', ['/javascript/index'], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a('view', ['/audit/javascript/index'], ['class' => 'btn btn-default btn-xs']) ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <p>Any javascript errors will be logged to the
                        <code>audit_javascript</code> table. You can also log any data you want from your javascript.
                    </p>

                    <p>To activate this feature register the <code>JSLoggingAsset</code> asset bundle.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        Errors
                        <span class="pull-right">
                            <?= Html::a('create', ['/error/index'], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a('view', ['/audit/error/index'], ['class' => 'btn btn-default btn-xs']) ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <p>PHP errors and exceptions are caught and logged to the <code>audit_error</code> table.</p>
                    <p>To activate this feature add the <code>ErrorHandler</code> class to your Yii config array.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        cURL
                        <span class="pull-right">
                            <?= Html::a('create', ['/curl/index'], ['class' => 'btn btn-default btn-xs']) ?>
                            <?= Html::a('view', ['/audit/curl/index'], ['class' => 'btn btn-default btn-xs']) ?>
                        </span>
                    </h3>
                </div>
                <div class="panel-body">
                    <p>If you pass your cURL handles to the module, it will perform logging on the entire request.</p>
                    <p>Audit recognizes (and can pretty-print) XML, HTML, JSON and regular Query strings.</p>
                </div>
            </div>
        </div>
    </div>
</div>
