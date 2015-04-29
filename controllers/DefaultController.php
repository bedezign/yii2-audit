<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.sa-portal.inet.telenet.be
 * @copyright 2014 B&E DeZign
 */

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

    public function behaviors()
    {
        if ($this->module->accessUsers === null && $this->module->accessRoles === null)
            // No user authentication active, skip adding the filter
            return [];

        $rule = ['allow' => 'allow'];
        if ($this->module->accessRoles) {
            $rule['roles'] = $this->module->accessRoles;
        }
        if ($this->module->accessUsers) {
            $rule['matchCallback'] = function ($rule, $action) {
                return in_array(\Yii::$app->user->id, $action->controller->module->accessUsers);
            };
        }

        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    $rule
                ],
            ],
        ];
    }

    public function init()
    {
        \bedezign\yii2\audit\assets\AuditingAsset::register($this->view);

        parent::init();
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