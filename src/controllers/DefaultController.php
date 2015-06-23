<?php

namespace bedezign\yii2\audit\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\models\AuditTrailSearch;

class DefaultController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => $this->module->getAccessControlFilter()
        ];
    }

    public function beforeAction($action)
    {
        \bedezign\yii2\audit\assets\ViewerAsset::register($this->view);
        return parent::beforeAction($action);
    }

    /**
     * Lists all AuditEntry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuditEntrySearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditEntry model.
     * @param integer $id
     * @return mixed
     * @throws \HttpInvalidParamException
     */
    public function actionView($id)
    {
        $entry = AuditEntry::findOne($id);
        if ($entry) {
            return $this->render('view', ['entry' => $entry]);
        } else {
            throw new \HttpInvalidParamException('Invalid request number specified');
        }
    }

    /**
     * Displays a single AuditEntry model.
     * @param integer $id
     * @return mixed
     */
    public function actionDiff($id)
    {
        $model = AuditTrail::findOne($id);

        return $this->render('diff', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all AuditTrail models.
     * @return mixed
     */
    public function actionTrail()
    {
        $searchModel = new AuditTrailSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('trail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}
