<?php
/**
 *
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.sa-portal.inet.telenet.be
 * @category
 * @copyright 2014 B&E DeZign
 */

namespace bedezign\yii2\audit\controllers;

use Yii;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;

class RequestController extends \yii\web\Controller
{
    use \bedezign\yii2\audit\components\ControllerTrait;


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
}