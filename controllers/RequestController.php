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

use bedezign\yii2\audit\models\AuditEntry;

class RequestController extends \yii\web\Controller
{
    use \bedezign\yii2\audit\components\ControllerTrait;

    public function actionIndex($entry = null)
    {
        if ($entry) {
            $entry = AuditEntry::findOne($entry);
            if ($entry) {
                echo $this->render('entry', ['entry' => $entry]);
                return;
            }
            else
                throw new \HttpInvalidParamException('Invalid request number specified');
        }
        $this->redirect(['overview']);
    }
}