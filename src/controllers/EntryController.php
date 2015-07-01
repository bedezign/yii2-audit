<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use Yii;
use yii\web\NotFoundHttpException;

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
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * Displays a single AuditEntry model.
     * @param integer $id
     * @param string  $panel
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($id, $panel = '')
    {
        $model = $this->loadData($id);
        $storedPanels = $model->associatedPanels;
        $panels = array_intersect_key($this->module->panels, array_flip($storedPanels));

        if (isset($panels[$panel]))
            $activePanel = $panel;
        else {
            reset($panels);
            $activePanel = key($panels);
        }

        $this->summary['tag'] = $id;
        // @TODO: Add unknown panels as "generic data"

        return $this->render('view', [
            'id'          => $id,
            'model'       => $model,
            'activePanel' => $activePanel ? $panels[$activePanel] : null,
            'panels'      => $panels,
        ]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = [];
        foreach ($this->module->panels as $panel) {
            $actions = array_merge($actions, $panel->actions);
        }
        return $actions;
    }

    /**
     * @param $id
     * @return AuditEntry
     * @throws NotFoundHttpException
     */
    public function loadData($id)
    {
        /** @var AuditEntry $model */
        $model = AuditEntry::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested entry does not exist.');
        }

        // Make sure the view-only panels are active as well
        $module = $this->module;
        $module->initPanels(true);
        foreach ($module->panels as $panelId => $panel) {
            $panel->tag = $id;
            $panel->model = $model;
            $panel->load($model->typeData($panelId));
        }

        return $model;
    }
}
