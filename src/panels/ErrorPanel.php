<?php

namespace bedezign\yii2\audit\panels;

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\components\panels\Panel;
use bedezign\yii2\audit\models\AuditError;
use bedezign\yii2\audit\models\AuditErrorSearch;
use Yii;
use yii\grid\GridViewAsset;

/**
 * ErrorPanel
 * @package bedezign\yii2\audit\panels
 */
class ErrorPanel extends Panel
{
    private $_exceptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->module->registerFunction('exception', function($e) {
            $entry = $this->module->getEntry(true);
            return $entry ? $this->log($entry->id, $e) : null;
        });

        $this->module->registerFunction('errorMessage', function ($message, $code = 0, $file = '', $line = 0, $trace = []) {
            $entry = $this->module->getEntry(true);
            return $entry ? $this->logMessage($entry->id, $message, $code, $file, $line, $trace) : null;
        });
    }


    /**
     * Log an exception
     *
     * @param int        $entry_id      Entry to associate the error with
     * @param \Exception|\Throwable  $exception
     * @return null|static
     */
    public function log($entry_id, $exception)
    {
        // Only log each exception once
        $exceptionId = spl_object_hash($exception);
        if (in_array($exceptionId, $this->_exceptions))
            return true;

        // If this is a follow up exception, make sure to log the base exception first
        if ($exception->getPrevious())
            $this->log($entry_id, $exception->getPrevious());

        $error = new AuditError();
        $error->entry_id    = $entry_id;
        $error->message     = $exception->getMessage();
        $error->code        = $exception->getCode();
        $error->file        = $exception->getFile();
        $error->line        = $exception->getLine();
        $error->trace       = Helper::cleanupTrace($exception->getTrace());
        $error->hash        = Helper::hash($error->message . $error->file . $error->line);

        $this->_exceptions[] = $exceptionId;

        return $error->save(false) ? $error : null;
    }

    /**
     * Log a regular error message
     *
     * @param int        $entry_id      Entry to associate the error with
     * @param string     $message
     * @param int        $code
     * @param string     $file
     * @param int        $line
     * @param array      $trace         Stack trace to include. Use `Helper::generateTrace()` to create it.
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