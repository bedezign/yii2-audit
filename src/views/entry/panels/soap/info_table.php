<?php

use yii\helpers\Html;
use yii\helpers\VarDumper;
use yii\helpers\Url;



$formatter = \Yii::$app->formatter;

if (isset($request['error']) && isset($request['error'][1])) {
    $error = \bedezign\yii2\audit\models\AuditError::findOne($request['error'][1]);
    $request['error'] = Html::a('[' . $request['error'][0] . '] ' . $error->message, ['error/view', 'id' => $error->id]);
}

?>
<div class="table-responsive">
    <table class="table table-condensed table-bordered table-striped table-hover request-table" style="table-layout: fixed;">
        <thead>
            <tr>
                <th>Name</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($request as $name => $value): ?>
            <tr>
                <th><?= Html::encode(\yii\helpers\Inflector::humanize($name)) ?></th>
                <td>
<?php
    if (is_array($value) || is_object($value))
        $value = \yii\helpers\ArrayHelper::toArray($value);
    elseif ($name == 'duration')
        $value = number_format($value, 2) . 's';
    echo $name == 'error' ? $value : $formatter->asText(is_scalar($value) ? $value : VarDumper::dumpAsString($value));
?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

