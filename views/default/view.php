<?php

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $entry */

use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\models\AuditTrail;

$this->title = Yii::t('audit', 'Audit Entry #{entry}', ['entry' => $entry->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('audit', 'Audit Entries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::tag('h1', $this->title) ?>

<div class="row">
    <div class="col-md-10">
<?php
        echo Html::tag('h2', Yii::t('audit', 'Request'), ['id' => 'entry', 'class' => 'hashtag']);
        echo DetailView::widget([
            'model' => $entry,
            'attributes' =>
            [
                'id',
                [
                    'label' => $entry->getAttributeLabel('user_id'),
                    'value' => intval($entry->user_id) ? $entry->user_id : Yii::t('audit', 'Guest'),
                ],
                'ip',
                'created',
                [ 'attribute' => 'start_time', 'format' => 'datetime' ],
                [ 'attribute' => 'end_time', 'format' => 'datetime' ],
                [ 'attribute' => 'duration', 'format' => 'decimal' ],
                'referrer',
                'origin',
                'url',
                'route',
                [ 'attribute' => 'memory', 'format' => 'shortsize' ],
                [ 'attribute' => 'memory_max', 'format' => 'shortsize' ],
            ]
        ]);

        if (count($entry->extraData)) {
            $attributes = [];
            foreach ($entry->extraData as $data) {
                $attributes[] = [
                    'label'     => $data->name,
                    'value'     => Helper::formatValue($data->data),
                    'format'    => 'raw',
                ];
            }

            echo Html::tag('h2', Yii::t('audit', 'Extra data ({i})', ['i' => count($entry->extraData)]), ['id' => 'extra-data', 'class' => 'hashtag']);
            echo DetailView::widget(['model' => $entry, 'attributes' => $attributes]);
        }

        if (count($entry->trail)) {
            $dataProvider = new ActiveDataProvider([
                'query' => AuditTrail::find()->where(['audit_id' => $entry->id]),
                'pagination' => [
                    'pageSize' => 1000,
                ],
            ]);

            echo Html::tag('h2', Yii::t('audit', 'Trail ({i})', ['i' => count($entry->trail)]), ['id' => 'trail', 'class' => 'hashtag']);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'action',
                    'model',
                    'model_id',
                    'field',
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

        if (count($entry->linkedErrors)) {
            echo Html::tag('h2', Yii::t('audit', 'Errors ({i})', ['i' => count($entry->errors)]), ['id' => 'errors', 'class' => 'hashtag']);

            foreach ($entry->linkedErrors as $i => $error) {
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

        if (count($entry->javascript)) {
            echo Html::tag('h2', Yii::t('audit', 'Javascript ({i})', ['i' => count($entry->javascript)]), ['id' => 'javascript', 'class' => 'hashtag']);

            foreach ($entry->javascript as $i => $javascript) {
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

        $types = [
            'env'     => '$_SERVER',
            'session' => '$_SESSION',
            'cookies' => '$_COOKIES',
            'files'   => '$_FILES',
            'get'     => '$_GET',
            'post'    => '$_POST',
        ];

        foreach ($entry->data as $type => $values) {
            $attributes = [];
            foreach ($values as $name => $value) {
                $attributes[] = [
                    'label'     => $name,
                    'value'     => Helper::formatValue($value),
                    'format'    => 'raw',
                ];
            }

            echo Html::tag('h2', $types[$type], ['id' => $type, 'class' => 'hashtag']);
            echo DetailView::widget([
                'model' => $entry,
                'attributes' => $attributes,
                'template' => '<tr><th>{label}</th><td style="word-break:break-word;">{value}</td></tr>'
            ]);
        }
?>
    </div>
    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked affix">
          <li><a href="#entry"><?= Yii::t('auditing', 'Request') ?></a></li>

          <?php if (count($entry->extraData)): ?>
              <li><a href="#extra-data"><?= Yii::t('auditing', 'Extra data ({i})', ['i' => count($entry->extraData)]) ?></a></li>
          <?php endif ?>

          <?php if (count($entry->trail)): ?>
              <li><a href="#trail"><?= Yii::t('audit', 'Trail ({i})', ['i' => count($entry->trail)]) ?></a></li>
          <?php endif ?>

          <?php if (count($entry->linkedErrors)): ?>
              <li><a href="#errors"><?= Yii::t('audit', 'Errors ({i})', ['i' => count($entry->linkedErrors)]) ?></a></li>
          <?php endif ?>

          <?php if (count($entry->javascript)): ?>
              <li><a href="#javascript"><?= Yii::t('audit', 'Javascript ({i})', ['i' => count($entry->javascript)]) ?></a></li>
          <?php endif ?>

          <?php foreach ($entry->data as $type => $values): ?>
              <li><a href="#<?= $type ?>"><?= $types[$type] . ' (' . count($entry->data[$type]) . ')' ?></a></li>
          <?php endforeach ?>
        </ul>
    </div>
</div>