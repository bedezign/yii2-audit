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
        echo DetailView::widget([
            'model' => $model,
            'attributes' =>
            [
                'id',
                [
                    'label' => $model->getAttributeLabel('user_id'),
                    'value' => Audit::current()->getUserIdentifier($model->user_id),
                ],
                'ip',
                'created',
                [ 'attribute' => 'duration', 'format' => 'decimal' ],
                'referrer',
                'redirect',
                'url',
                'route',
                [ 'attribute' => 'memory_max', 'format' => 'shortsize' ],
            ]
        ]);

?>
            <div class="row">
                <div class="col-md-2">
                    <div class="list-group">
                        <?php
                        foreach ($panels as $id => $panel) {
                            $label = '<i class="glyphicon glyphicon-chevron-right"></i>' . Html::encode($panel->getName());
                            echo Html::a($label, ['view', 'id' => $model->id, 'panel' => $id], [
                                'class' => $panel === $activePanel ? 'list-group-item active' : 'list-group-item',
                            ]);
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-10">
                    <?= $activePanel->getDetail() ?>
                </div>

<?php
__halt_compiler();
        foreach ($model->extraData as $data) {
            $attributes = [];
            foreach ($data->data as $name => $value) {
                $attributes[] = [
                    'label' => $name,
                    'value' => Helper::formatValue($value),
                    'format' => 'raw',
                ];
            }

            echo Html::tag('h2', $data->type . ' (' . count($attributes) . ')', ['id' => $data->type, 'class' => 'hashtag']);
            echo DetailView::widget([
                'model' => $data,
                'attributes' => $attributes,
                'template' => '<tr><th>{label}</th><td style="word-break:break-word;">{value}</td></tr>'
            ]);
        }

        if (count($model->trail)) {
            $dataProvider = new ActiveDataProvider([
                'query' => AuditTrail::find()->where(['entry_id' => $model->id]),
                'pagination' => [
                    'pageSize' => 1000,
                ],
            ]);

            echo Html::tag('h2', Yii::t('audit', 'Trail ({i})', ['i' => count($model->trail)]), ['id' => 'trail', 'class' => 'hashtag']);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'action',
                    'model',
                    'model_id',
                    'field',
                    [
                        'label' => Yii::t('audit', 'Diff'),
                        'value' => function ($model) {
                            /** @var AuditTrail $model */
                            return $model->getDiffHtml();
                        },
                        'format' => 'raw',
                    ],
                    'stamp',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                return Html::a(
                                    Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']),
                                    ['diff', 'id' => $model->id],
                                    [
                                        'class' => '',
                                        'data' => [
                                            'toggle' => 'modal',
                                        ]
                                    ]
                                );
                            }
                        ]
                    ]
                ]
            ]);
        }

        if (count($model->linkedErrors)) {
            echo Html::tag('h2', Yii::t('audit', 'Errors ({i})', ['i' => count($model->linkedErrors)]), ['id' => 'errors', 'class' => 'hashtag']);

            foreach ($model->linkedErrors as $i => $error) {
                echo Html::tag('h3', Yii::t('audit', 'Error #{i}', ['i' => $i + 1]));
                echo DetailView::widget([
                    'model' => $error,
                    'attributes' => [
                        'message',
                        'code',
                        [
                            'label' => Yii::t('audit', 'Location'),
                            'value' => $error->file . '(' . $error->line . ')'
                        ]
                    ]
                ]);
            }
        }

        if (count($model->javascript)) {
            echo Html::tag('h2', Yii::t('audit', 'Javascript ({i})', ['i' => count($model->javascript)]), ['id' => 'javascript', 'class' => 'hashtag']);

            foreach ($model->javascript as $i => $javascript) {
                echo Html::tag('h3', Yii::t('audit', 'Entry #{i}', ['i' => $i + 1]));
                echo DetailView::widget(['model' => $javascript, 'attributes' => [
                    'type',
                    'message',
                    'origin',
                    [
                        'attribute' => 'data',
                        'value'     => Helper::formatValue($javascript->data),
                        'format'    => 'raw',
                    ]
                ]]);
            }
        }

?>
    </div>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked affix">
          <li><a href="#entry"><?= Yii::t('audit', 'Request') ?></a></li>

          <?php foreach ($model->extraData as $extraData): ?>
              <li><a href="#<?= $extraData->type ?>"><?= $extraData->type . ' (' . count($extraData->data) . ')' ?></a></li>
          <?php endforeach ?>

          <?php if (count($model->trail)): ?>
              <li><a href="#trail"><?= Yii::t('audit', 'Trail ({i})', ['i' => count($model->trail)]) ?></a></li>
          <?php endif ?>

          <?php if (count($model->linkedErrors)): ?>
              <li><a href="#errors"><?= Yii::t('audit', 'Errors ({i})', ['i' => count($model->linkedErrors)]) ?></a></li>
          <?php endif ?>

          <?php if (count($model->javascript)): ?>
              <li><a href="#javascript"><?= Yii::t('audit', 'Javascript ({i})', ['i' => count($model->javascript)]) ?></a></li>
          <?php endif ?>
        </ul>
    </div>
</div>
