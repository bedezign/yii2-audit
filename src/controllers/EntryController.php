<?php

namespace bedezign\yii2\audit\controllers;

use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\components\web\Controller;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use Yii;
use yii\helpers\ArrayHelper;
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
        list($model, $panels) = $this->loadData($id);
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
     * @return AuditEntry|Panel[]
     * @throws NotFoundHttpException
     */
    public function loadData($id)
    {
        /** @var AuditEntry $model */
        $model = AuditEntry::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('The requested entry does not exist.');
        }

        // Why the separate panel list here?
        // Because we don't want to interfere with the modules' regular configuration.
        // We might as well be viewing an entry that has data for more panels than those who are currently active.
        // Updating the actual module panels would mean that for all audit viewing that panel would become active again

        $panels = $this->module->loadPanels($this->module->getPanelIdentifiers());
        $activePanels = [];
        $data = ArrayHelper::getColumn($model->data, 'data');
        foreach ($panels as $panelId => $panel)
            if ($panel->hasEntryData($model)) {
                $panel->tag = $id;
                $panel->model = $model;
                $panel->load(isset($data[$panelId]) ? $data[$panelId] : null);
                $activePanels[$panelId] = $panel;
            }

        return [$model, $activePanels];
    }
}
