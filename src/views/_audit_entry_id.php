<?php

use bedezign\yii2\audit\Audit;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var bedezign\yii2\audit\models\AuditEntry $entry */

if ($auditEntry = Audit::getInstance()->getEntry()) {
    $style = YII_DEBUG ? '' : 'color:transparent;';
    if (Audit::getInstance()->checkAccess()) {
        echo Html::a('audit-' . $auditEntry->id, ['/audit/default/view', 'id' => $auditEntry->id], ['style' => $style]);
    } else {
        echo Html::tag('span', 'audit-' . $auditEntry->id, ['style' => $style]);
    }
}
