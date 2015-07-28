<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditErrorSearch;
use Exception;
use Yii;
use yii\grid\GridViewAsset;

/**
 * ErrorPanel
 * @package bedezign\yii2\audit\panels
 */
class ErrorPanel extends Panel
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module->registerFunction('exception', function(\Exception $e) {
            $entry = $this->module->getEntry(true);
            return $entry ? $this->log($entry->id, $e) : null;
        });

        $this->module->registerFunction('errorMessage', function ($message, $code = 0, $file = '', $line = 0, $trace = []) {
            $entry = $this->module->getEntry(true);
            return $entry ? $this->logMessage($entry->id, $message, $code, $file, $line, $trace) : null;
        });
    }


    /**
     * @param int $entry_id
     * @param Exception  $exception
     * @return null|static
     */
    public function log($entry_id, Exception $exception)
    {
        $error = new AuditError();
        $error->entry_id    = $entry_id;
        $error->message     = $exception->getMessage();
        $error->code        = $exception->getCode();
        $error->file        = $exception->getFile();
        $error->line        = $exception->getLine();
        $error->trace       = Helper::cleanupTrace($exception->getTrace());
        $error->hash        = Helper::hash($error->message . $error->file . $error->line);
        return $error->save(false) ? $error : null;
    }

    /**
     * @param int        $entry_id
     * @param string     $message
     * @param int        $code
     * @param string     $file
     * @param int        $line
     * @param array      $trace
     * @return null|static
     */
    public function logMessage($entry_id, $message, $code = 0, $file = '', $line = 0, $trace = [])
    {
        $error = new AuditError();
        $error->entry_id    = $entry_id;
        $error->message     = $message;
        $error->code        = $code;
        $error->file        = $file;
        $error->line        = $line;
        $error->trace       = Helper::cleanupTrace($trace);
        $error->hash        = Helper::hash($error->message . $error->file . $error->line);
        return $error->save(false) ? $error : null;
    }

    /**
     * @inheritdoc
     */
    public function hasEntryData($entry)
    {
        return count($entry->linkedErrors) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return \Yii::t('audit', 'Errors');
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getName() . ' <small>(' . count($this->_model->linkedErrors) . ')</small>';
    }

    /**
     * @inheritdoc
     */
    public function getDetail()
    {
        $searchModel = new AuditErrorSearch();
        $params = \Yii::$app->request->getQueryParams();
        $params['AuditErrorSearch']['entry_id'] = $params['id'];
        $dataProvider = $searchModel->search($params);

        return \Yii::$app->view->render('panels/error/detail', [
            'panel'        => $this,
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getIndexUrl()
    {
        return ['error/index'];
    }

    /**
     * @inheritdoc
     */
    public function getChart()
    {
        return \Yii::$app->view->render('panels/error/chart', [
            'panel' => $this,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function registerAssets($view)
    {
        GridViewAsset::register($view);
    }

    /**
     * @inheritdoc
     */
    public function cleanup($maxAge = null)
    {
        $maxAge = $maxAge !== null ? $maxAge : $this->maxAge;
        if ($maxAge === null)
            return false;
        return AuditError::deleteAll([
            '<=', 'created', date('Y-m-d 23:59:59', strtotime("-$maxAge days"))
        ]);
    }

}