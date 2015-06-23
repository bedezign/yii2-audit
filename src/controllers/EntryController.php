<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use Yii;

/**
 * EntryController
 * @package bedezign\yii2\audit\controllers
 */
class EntryController extends Controller
{
    /**
     * @var array fake summary data so the debug panels work
     */
    public $summary = ['tag' => ''];

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
    public function actionView($id, $panel = '')
    {
        $model = AuditEntry::findOne($id);
        if (!$model) {
            throw new \HttpInvalidParamException('Invalid request number specified');
        }

        $module = $this->module;

        // Make sure the view-only panels are active as well
        $module->initPanels(true);
        $storedPanels = $model->associatedPanels;
        $panels = array_intersect_key($module->panels, array_flip($storedPanels));

        if (isset($panels[$panel]))
            $activePanel = $panel;
        else {
            reset($panels);
            $activePanel = key($panels);
        }

        $panels[$activePanel]->load($model->typeData($activePanel));

        // @TODO: Add unknown panels as "generic data"

        return $this->render('view', [
            'id' => $id,
            'model' => $model,
            'activePanel' => $panels[$activePanel],
            'panels' => $panels,
        ]);
    }
}
