<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\models\AuditMailSearch;
use Yii;
use yii\base\Event;
use yii\grid\GridViewAsset;
use yii\mail\BaseMailer;

/**
 * MailPanel
 * @package bedezign\yii2\audit\panels
 */
class MailPanel extends Panel
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(BaseMailer::className(), BaseMailer::EVENT_AFTER_SEND, function ($event) {
            AuditMail::record($event);
        });
    }

    public function hasEntryData($entry)
    {
        return count($entry->mails) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('audit', 'Mail');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->mails) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new AuditMailSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditMailSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/mail/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

}