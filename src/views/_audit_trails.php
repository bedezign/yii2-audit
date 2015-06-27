<?php

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\Access;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\models\AuditTrailSearch;
use bedezign\yii2\audit\web\AuditAsset;
use yii\db\BaseActiveRecord;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Pjax;

/**
 * @var View $this
 * @var BaseActiveRecord $model
 * @var array $params
 */

$params = !empty($params) ? $params : Yii::$app->request->get();
$this->registerAssetBundle(AuditAsset::className());
?>
<?php
$auditTrailSearch = new AuditTrailSearch();
//$auditTrailDataProvider = $auditTrailSearch->search($params, $model->getAuditTrails());
$auditTrailDataProvider = $auditTrailSearch->search($params);
$auditTrailDataProvider->pagination = ['pageSize' => 20, 'pageParam' => 'page-auditTrails'];
$auditTrailDataProvider->sort = ['defaultOrder' => ['id' => SORT_DESC]];
?>
<?php Pjax::begin([
    'id' => 'pjax-AuditTrails',
    'enableReplaceState' => false,
    'linkSelector' => '#pjax-AuditTrails ul.pagination a, th a',
]) ?>
<?= '<div class="table-responsive">' . GridView::widget([
    'layout' => '{summary}{pager}<br/>{items}{pager}',
    'dataProvider' => $auditTrailDataProvider,
    'filterModel' => $auditTrailSearch,
    'columns' => [
        [
            'attribute' => 'user_id',
            'value' => function ($data) {
                return Audit::getInstance()->getUserIdentifier($data->user_id);
            },
            'format' => 'raw',
        ],
        [
            'attribute' => 'entry_id',
            'value' => function ($model) {
                /** @var AuditTrail $model */
                if (Access::checkAccess()) {
                    return Html::a($model->entry_id, ['/audit/entry/view', 'id' => $model->entry_id]);
                }
                return $model->entry_id;
            },
            'format' => 'raw',
        ],
        'action',
        [
            'attribute' => 'model',
            //'value' => function ($model) {
            //    /** @var AuditTrail $model */
            //    return str_replace('app\\models\\', '', $model->model_name);
            //}
        ],
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
        'created',
    ]
]) . '</div>' ?>

<?php
// row grouping
ob_start();
?>
<script>
    // grouping not working
    // see http://www.hafidmukhlasin.com/2015/02/09/yii2-create-grouping-in-gridview-from-scracth-with-jquery/
    //        var gridview_id = "#pjax-AuditTrails .grid-view"; // specific gridview
    //        var columns = [1]; // index column that will grouping, (user_id, entry_id)
    //
    //        var column_data = [];
    //        column_start = [];
    //        rowspan = [];
    //
    //        for (var i = 0; i < columns.length; i++) {
    //            column = columns[i];
    //            column_data[column] = "";
    //            column_start[column] = null;
    //            rowspan[column] = 1;
    //        }
    //
    //        var row = 1;
    //        $(gridview_id + " table > tbody  > tr").each(function () {
    //            var col = 1;
    //            $(this).find("td").each(function () {
    //                for (var i = 0; i < columns.length; i++) {
    //                    if (col == columns[i]) {
    //                        if (column_data[columns[i]] == $(this).html()) {
    //                            $(this).remove();
    //                            rowspan[columns[i]]++;
    //                            $(column_start[columns[i]]).attr("rowspan", rowspan[columns[i]]);
    //                        }
    //                        else {
    //                            column_data[columns[i]] = $(this).html();
    //                            rowspan[columns[i]] = 1;
    //                            column_start[columns[i]] = $(this);
    //                        }
    //                    }
    //                }
    //                col++;
    //            });
    //            row++;
    //        });
</script>
<?php
// get contents
$js = ob_get_clean();
$js = str_replace(array('<script>', '</script>'), '', $js);
// register the js script
$this->registerJs($js . ';', View::POS_READY);
?>

<?php Pjax::end() ?>
