<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Helper;
use Yii;

/**
 * Class AuditEntry
 * @package bedezign\yii2\audit\models
 *
 * @property int    $id
 * @property string $created
 * @property float  $duration
 * @property int    $user_id        0 means anonymous
 * @property string $ip
 * @property string $referrer
 * @property string $redirect
 * @property string $url
 * @property string $route
 * @property int    $memory_max
 * @property string $request_method
 */
class AuditEntry extends AuditModel
{
    protected $start_time;
    protected $autoSerialize = false;

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

    public function getAssociatedPanels()
    {
        $panels = AuditData::findEntryTypes($this->id);
        if (count($this->linkedErrors))
            $panels[] = 'errors';

        if (count($this->javascript))
            $panels[] = 'javascript';

        if (count($this->trail))
            $panels[] = 'trail';

        return $panels;
    }

    public function typeData($type)
    {
        $record = AuditData::findForEntry($this->id, $type);
        return $record ? $record->data : null;
    }

    /**
     * Returns all linked AuditData instances
     * @return AuditData[]
     */
    public function getExtraData()
    {
        return static::hasMany(AuditData::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditError instances
     * (Called `linkedErrors()` to avoid confusion with the `getErrors()` method)
     * @return AuditError[]
     */
    public function getLinkedErrors()
    {
        return static::hasMany(AuditError::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditError instances
     * @return AuditError[]
     */
    public function getTrail()
    {
        return static::hasMany(AuditTrail::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditJavascript instances
     * @return AuditJavascript[]
     */
    public function getJavascript()
    {
        return static::hasMany(AuditJavascript::className(), ['entry_id' => 'id']);
    }

    public function addData($type, $data, $compact = true)
    {
        $this->addBatchData([$type => $data], $compact);
    }

    public function addBatchData($batchData, $compact = true)
    {
        $columns = ['entry_id', 'type', 'data', 'packed'];
        $rows = [];
        foreach ($batchData as $type => $data) {
            $rows[] = [$this->id, $type, Helper::serialize($data, $compact), 1];
        }
        Yii::$app->db->createCommand()->batchInsert(AuditData::tableName(), $columns, $rows)->execute();
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $app                = Yii::$app;
        $request            = $app->request;

        $this->route        = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        $this->start_time   = YII_BEGIN_TIME;

        if ($request instanceof \yii\web\Request) {
            $user                 = $app->user;
            $this->user_id        = $user->isGuest ? 0 : $user->id;
            $this->url            = $request->url;
            $this->ip             = $request->userIP;
            $this->referrer       = $request->referrer;
            $this->request_method = $_SERVER['REQUEST_METHOD'];
        }
        else if ($request instanceof \yii\console\Request) {
            $this->url            = $request->scriptFile;
            $this->request_method = 'CLI';
        }

        $this->save(false);
    }

    public function finalize()
    {
        $this->duration = microtime(true) - $this->start_time;
        $this->memory_max = memory_get_peak_usage();

        $response = Yii::$app->response;
        if ($response instanceof \yii\web\Response) {
            $this->redirect = $response->headers->get('location');
        }

        return $this->save(false, ['duration', 'memory_max', 'redirect']);
    }

    public function attributeLabels()
    {
        return
        [
            'id'             => Yii::t('audit', 'Entry ID'),
            'created'        => Yii::t('audit', 'Added'),
            'ip'             => Yii::t('audit', 'IP'),
            'duration'       => Yii::t('audit', 'Duration'),
            'user_id'        => Yii::t('audit', 'User'),
            'memory_max'     => Yii::t('audit', 'Memory'),
            'request_method' => Yii::t('audit', 'Request Method'),
        ];
    }
}
