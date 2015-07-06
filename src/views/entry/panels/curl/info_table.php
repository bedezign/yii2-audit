<?php

use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Response;

$formatter = \Yii::$app->formatter;

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

    if (preg_match('/_{0,1}(size|length)_{0,1}/', $name))
        echo $formatter->asSize($value);
    else if (strpos($name, 'speed') !== false)
        echo $formatter->asSize($value) . '/s';
    else if (strpos($name, 'time') !== false && is_numeric($value) && $value >= 0)
        echo number_format($value, 2) . 's';
    else if ($name == 'http_code') {
        $type = substr($value, 0, 1);
        echo Html::tag('span', $value . (isset(Response::$httpStatuses[$value]) ? (' (' . Response::$httpStatuses[$value] . ')') : ''),
            ['style' => 'color: '. ($type == 2 ? 'green' : ($type == 4 || $type == 5 ? 'red' : 'orange'))]);
    }
    else
        echo htmlspecialchars(VarDumper::dumpAsString($value), ENT_QUOTES|ENT_SUBSTITUTE, \Yii::$app->charset, true)
?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

