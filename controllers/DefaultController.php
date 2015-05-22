<?php

namespace bedezign\yii2\audit\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use bedezign\yii2\audit\models\AuditTrail;

class DefaultController extends \yii\web\Controller
{
    use \bedezign\yii2\audit\components\ControllerTrait;

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
     */
    public function actionView($id)
    {
        $entry = AuditEntry::findOne($id);
        if ($entry) {
            return $this->render('view', ['entry' => $entry]);
        } else {
            throw new \HttpInvalidParamException('Invalid request number specified');
        }

        $this->redirect(['index']);
    }

    /**
     * Displays a single AuditEntry model.
     * @param integer $id
     * @return mixed
     */
    public function actionDiff($id)
    {
        $model = AuditTrail::findOne($id);

        $old = explode("\n", $model->old_value);
        $new = explode("\n", $model->new_value);

        foreach ($old as $i => $line) {
            $old[$i] = rtrim($line, "\r\n");
        }
        foreach ($new as $i => $line) {
            $new[$i] = rtrim($line, "\r\n");
        }

        $diff = new \Diff($old, $new);

        return $this->render('diff', [
            'model' => $model,
            'diff' => $diff->render(new \Diff_Renderer_Html_Inline)]
        );
    }
}