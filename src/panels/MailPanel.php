<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\PanelTrait;
use Yii;
use yii\debug\models\search\Mail;
use yii\widgets\ActiveFormAsset;

/**
 * MailPanel
 * @package bedezign\yii2\audit\panels
 */
class MailPanel extends \yii\debug\panels\MailPanel
{
    use PanelTrait;

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new Mail();
        $dataProvider = $searchModel->search(Yii::$app->request->get(), $this->data);

        return Yii::$app->view->render('panels/mail/detail', [
            'panel'         => $this,
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel
        ]);
    }

    /**
     *
     */
    public function registerAssets()
    {
        ActiveFormAsset::register(Yii::$app->getView());
    }
}