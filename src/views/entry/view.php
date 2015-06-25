<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $model */

use bedezign\yii2\audit\Audit;
use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\models\AuditTrail;

$this->title = Yii::t('audit', 'Entry #{id}', ['id' => $model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit'), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = '#' . $model->id;

?>

<?= Html::tag('h1', $this->title) ?>

<div class="row">
    <div class="col-md-12">
<?php
    echo Html::tag('h2', Yii::t('audit', 'Request'), ['id' => 'entry', 'class' => 'hashtag']);

    if ($model->request_method == 'CLI') {
        $attributes = [
            'id',
            [
                'label' => $model->getAttributeLabel('user_id'),
                'value' => Audit::current()->getUserIdentifier($model->user_id),
            ],
            'route',
            'request_method',
            'url',
            ['attribute' => 'duration', 'format' => 'decimal'],
            ['attribute' => 'memory_max', 'format' => 'shortsize'],
            'created',
        ];
    } else {
        $attributes = [
            'id',
            [
                'label' => $model->getAttributeLabel('user_id'),
                'value' => Audit::current()->getUserIdentifier($model->user_id),
            ],
            'ip',
            'route',
            'request_method',
            'ajax',
            'url',
            'referrer',
            'redirect',
            ['attribute' => 'duration', 'format' => 'decimal'],
            ['attribute' => 'memory_max', 'format' => 'shortsize'],
            'created',
        ];
    }

    echo DetailView::widget([
        'model' => $model,
        'attributes' => $attributes
    ]);
?>
<div class="row">
    <div class="col-md-2">
        <div class="list-group">
            <?php
            foreach ($panels as $id => $panel) {
                $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . $panel->getName();
                echo Html::a($label, ['view', 'id' => $model->id, 'panel' => $id], [
                    'class' => $panel === $activePanel ? 'list-group-item active' : 'list-group-item',
                ]);
            }
            ?>
        </div>
    </div>
    <div class="col-md-10">
        <?php if ($activePanel) echo $activePanel->getDetail(); ?>
    </div>
</div>