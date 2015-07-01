<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\models\AuditTrailSearch;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * TrailController
 * @package bedezign\yii2\audit\controllers
 */
class TrailController extends Controller
{
    /**
     * Lists all AuditTrail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditTrailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditTrail model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = AuditTrail::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested trail does not exist.');
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }
}
