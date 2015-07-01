<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditMail;
use bedezign\yii2\audit\models\AuditMailSearch;
use Yii;
use yii\web\HttpException;

/**
 * MailController
 * @package bedezign\yii2\audit\controllers
 */
class MailController extends Controller
{
    /**
     * Lists all AuditMail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditMailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditMail model.
     * @param integer $id
     * @return mixed
     * @throws HttpException
     */
    public function actionView($id)
    {
        $model = AuditMail::findOne($id);
        if (!$model) {
            throw new HttpException(404, 'The requested page does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
