<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Helper;

/**
 * Class AuditEntry
 * @package bedezign\yii2\audit\models
 *
 * @property int    $id
 * @property string $created
 * @property float  $start_time
 * @property float  $end_time
 * @property float  $duration
 * @property int    $user_id        0 means anonymous
 * @property string $ip
 * @property string $referrer
 * @property string $origin
 * @property string $url
 * @property string $route
 * @property string $data           Compressed data collection of everything incoming
 * @property int    $memory
 * @property int    $memory_max
 * @property string $request_method
 */
class AuditEntry extends AuditModel
{
    public static function tableName()
    {
        return '{{%audit_entry}}';
    }

    public static function create($initialise = true)
    {
        $entry = new static;
        if ($initialise)
            $entry->record();

        return $entry;
    }

    /**
     * Returns all linked AuditData instances
     * @return AuditData[]
     */
    public function getUser()
    {
        return static::hasOne(User::className(), ['user_id' => 'id']);
    }

    /**
     * Returns all linked AuditData instances
     * @return AuditData[]
     */
    public function getExtraData()
    {
        return static::hasMany(AuditData::className(), ['audit_id' => 'id']);
    }

    /**
     * Returns all linked AuditError instances
     * @return AuditError[]
     */
    public function getLinkedErrors()
    {
        return static::hasMany(AuditError::className(), ['audit_id' => 'id']);
    }

    /**
     * Returns all linked AuditError instances
     * @return AuditError[]
     */
    public function getTrail()
    {
        return static::hasMany(AuditTrail::className(), ['audit_id' => 'id']);
    }

    /**
     * Returns all linked AuditJavascript instances
     * @return AuditJavascript[]
     */
    public function getJavascript()
    {
        return static::hasMany(AuditJavascript::className(), ['audit_id' => 'id']);
    }

    public function addData($name, $data, $type = null)
    {
        if ($this->isNewRecord)
            return null;

        $auditData = new AuditData;
        $auditData->entry = $this;
        $auditData->name  = $name;
        $auditData->data  = $data;
        $auditData->type  = $type;

        return $auditData->save() ? $auditData : null;
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $dataMap = ['get' => $_GET, 'post' => $_POST, 'cookies' => $_COOKIE, 'env' => $_SERVER, 'files' => $_FILES];

        $app                = \Yii::$app;
        $request            = $app->request;

        $this->route        = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        $this->start_time   = YII_BEGIN_TIME;

        if ($request instanceof \yii\web\Request) {
            $user                 = $app->user;
            $this->user_id        = $user->isGuest ? 0 : $user->id;
            $this->url            = $request->url;
            $this->ip             = $request->userIP;
            $this->referrer       = $request->referrer;
            $this->origin         = $request->headers->get('location');
            $this->request_method = $_SERVER['REQUEST_METHOD'];

            if (isset($_SESSION))
                $dataMap['session'] = $_SESSION;
        }
        else if ($request instanceof \yii\console\Request) {
            // Add extra link, makes it easier to detect
            $dataMap['params']    = $request->params;
            $this->url            = $request->scriptFile;
            $this->request_method = 'CLI';
        }

        // Record the incoming data
        $data = [];
        foreach ($dataMap as $index => $values)
            if (count($values))
                $data[$index] = Helper::compact($values);
        $this->data = $data;
    }

    public function finalize()
    {
        $this->end_time = microtime(true);
        $this->duration = $this->end_time - $this->start_time;
        $this->memory = memory_get_usage();
        $this->memory_max = memory_get_peak_usage();

        return $this->save(false, ['end_time', 'duration', 'memory', 'memory_max']);
    }

    public function attributeLabels()
    {
        return
        [
            'id'            => \Yii::t('audit', 'Entry Id'),
            'created'       => \Yii::t('audit', 'Added at'),
            'start_time'    => \Yii::t('audit', 'Start Time'),
            'end_time'      => \Yii::t('audit', 'End Time'),
            'duration'      => \Yii::t('audit', 'Request Duration'),
            'user_id'       => \Yii::t('audit', 'User'),
            'memory'        => \Yii::t('audit', 'Memory Usage'),
            'memory_max'    => \Yii::t('audit', 'Max. Memory Usage'),
            'request_method'=> \Yii::t('audit', 'Request Method'),
        ];
    }
}
