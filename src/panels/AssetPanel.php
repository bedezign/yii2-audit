<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\DataStoragePanelTrait;
use bedezign\yii2\audit\models\AuditData;
use Yii;

/**
 * AssetPanel
 * @package bedezign\yii2\audit\panels
 */
class AssetPanel extends \yii\debug\panels\AssetPanel
{
    use DataStoragePanelTrait;

    /**
     * @return string
     */
    public function getDetail()
    {
        return Yii::$app->view->render('@yii/debug/views/default/panels/assets/detail', [
            'panel' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        if (\Yii::$app->request instanceof \yii\web\Request) {
            return parent::save();
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditData::deleteAll('type = :type AND created <= :created', [
            ':type' => 'audit/asset',
            ':created' => date('Y-m-d 23:59:59', strtotime("-$maxAge days")),
        ]);
    }

}