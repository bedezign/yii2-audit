<?php

/** @var View $this */
/** @var Panel[] $panels */
/** @var Panel $activePanel */

/** @var AuditEntry $model */

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditEntry;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

foreach ($panels as $panel) {
    $panel->registerAssets($this);
}

$this->title = Yii::t('audit', 'Entry #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

?>

<?= Html::tag('h1', $this->title) ?>

<div class="row">
    <div class="col-md-6">
        <?php
        echo Html::tag('h2', Yii::t('audit', 'Request'), ['id' => 'entry', 'class' => 'hashtag']);
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'label' => $model->getAttributeLabel('user_id'),
                    'value' => Audit::getInstance()->getUserIdentifier($model->user_id),
                    'format' => 'raw',
                    'visible' => !$model->getRequestMethodIsCli()
                ],
                [
                    'attribute' => 'ip',
                    'visible' => !$model->getRequestMethodIsCli()
                ],
                'route',
                'request_method',
                [
                    'attribute' => 'ajax',
                    'format' => 'boolean',
                    'visible' => !$model->getRequestMethodIsCli()
                ],
                [
                    'label' => Yii::t('audit', 'Request URL'),
                    'value' => $model->getRequestUrl(),
                    'visible' => !$model->getRequestMethodIsCli()
                ],
            ]
        ]);
        ?>
    </div>
    <div class="col-md-6">
        <?php
        echo Html::tag('h2', Yii::t('audit', 'Profiling'), ['id' => 'entry', 'class' => 'hashtag']);
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'duration',
                    'format' => 'decimal'
                ],
                [
                    'attribute' => 'memory_max',
                    'format' => 'shortsize'
                ],
                'created',
            ]
        ]);
        ?>
    </div>
</div>

<?php Pjax::begin(['id' => 'audit-panels', 'timeout' => 0]); ?>
<div class="row">
    <div class="col-md-2">
        <div class="list-group">
            <?php
            foreach ($panels as $id => $panel) {
                $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . $panel->getLabel();
                echo Html::a($label, ['view', 'id' => $model->id, 'panel' => $id], [
                    'class' => $panel === $activePanel ? 'list-group-item active' : 'list-group-item',
                ]);
            }
            ?>
        </div>
    </div>
    <div class="col-md-10">
        <?php if ($activePanel) { ?>
            <?= $activePanel->getDetail(); ?>
            <input type="hidden" name="panel" value="<?= $activePanel->id ?>"/>
        <?php } ?>
    </div>
</div>
<?php Pjax::end(); ?>
