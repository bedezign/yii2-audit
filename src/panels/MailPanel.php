<?php

namespace bedezign\yii2\audit\panels;

use Yii;
use yii\debug\models\search\Mail;

class MailPanel extends \yii\debug\panels\MailPanel
{
    public function getDetail()
    {
        $searchModel = new Mail();
        $dataProvider = $searchModel->search(Yii::$app->request->get(), $this->data);

        return Yii::$app->view->render('panels/mail/detail', [
            'panel' => $this,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
}