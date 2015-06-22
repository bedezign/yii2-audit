<?php

use bedezign\yii2\audit\Auditing;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $entry */

if ($auditEntry = Auditing::current()->getEntry()) {
    $style = YII_DEBUG ? '' : 'color:transparent;';

    // link
    //echo Html::a('audit-' . $auditEntry->id, ['/auditing/default/view', 'id' => $auditEntry->id], ['style' => $style]);

    // span
    echo Html::tag('span', 'audit-' . $auditEntry->id, ['style' => $style]);
}
