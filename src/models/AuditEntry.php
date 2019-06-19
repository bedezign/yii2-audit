<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\db\ActiveRecord;
use bedezign\yii2\audit\components\Helper;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * AuditEntry
 * @package bedezign\yii2\audit\models
 *
 * @property int               $id
 * @property string            $created
 * @property float             $duration
 * @property int               $user_id        0 means anonymous
 * @property string            $ip
 * @property string            $route
 * @property int               $memory_max
 * @property string            $request_method
 * @property string            $ajax
 *
 * @property AuditError[]      $linkedErrors
 * @property AuditJavascript[] $javascripts
 * @property AuditTrail[]      $trails
 * @property AuditMail[]       $mails
 * @property AuditData[]       $data
 */
class AuditEntry extends ActiveRecord
{
    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_entry}}';
    }

    /**
     * @param bool $initialise
     * @return static
     */
    public static function create($initialise = true)
    {
        $entry = new static;
        if ($initialise)
            $entry->record();

        return $entry;
    }

    /**
     * Returns all linked AuditError instances
     * (Called `linkedErrors()` to avoid confusion with the `getErrors()` method)
     * @return ActiveQuery
     */
    public function getLinkedErrors()
    {
        return static::hasMany(AuditError::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditTrail instances
     * @return ActiveQuery
     */
    public function getTrails()
    {
        return static::hasMany(AuditTrail::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditMail instances
     * @return ActiveQuery
     */
    public function getMails()
    {
        return static::hasMany(AuditMail::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditJavascript instances
     * @return ActiveQuery
     */
    public function getJavascripts()
    {
        return static::hasMany(AuditJavascript::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked data records
     * @return ActiveQuery
     */
    public function getData()
    {
        return static::hasMany(AuditData::className(), ['entry_id' => 'id'])->indexBy('type');
    }

    /**
     * Writes a number of associated data records in one go.
     * @param      $batchData
     * @param bool $compact
     * @throws \yii\db\Exception
     */
    public function addBatchData($batchData, $compact = true)
    {
        $columns = ['entry_id', 'type', 'created', 'data'];
        $rows = [];
        $params = [];
        $date = date('Y-m-d H:i:s');
        // Some database like postgres depend on the data being escaped correctly.
        // PDO can take care of this if you define the field as a LOB (Large OBject), but unfortunately Yii does threat values
        // for batch inserts the same way. This code adds a number of literals instead of the actual values
        // so that they can be bound right before insert and still get escaped correctly
        foreach ($batchData as $type => $data) {
            $param = ':data_' . str_replace('/', '_', $type);
            $rows[] = [$this->id, $type, $date, new Expression($param)];
            $params[$param] = [Helper::serialize($data, $compact), \PDO::PARAM_LOB];
        }
        static::getDb()->createCommand()->batchInsert(AuditData::tableName(), $columns, $rows)->bindValues($params)->execute();
    }

    /**
     * @param $type
     * @param $data
     * @param bool|true $compact
     * @throws \yii\db\Exception
     */
    public function addData($type, $data, $compact = true)
    {
        // Make sure to mark data as a large object so it gets escaped
        $record = [
            'entry_id' => $this->id,
            'type' => $type,
            'created' => date('Y-m-d H:i:s'),
            'data' => [Helper::serialize($data, $compact), \PDO::PARAM_LOB],
        ];
        static::getDb()->createCommand()->insert(AuditData::tableName(), $record)->execute();
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $app = Yii::$app;
        $request = $app->request;

        $this->route = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        if ($request instanceof \yii\web\Request) {
            $this->user_id        = Audit::getInstance()->getUserId();
            $this->ip             = $this->getUserIP();
            $this->ajax           = $request->isAjax;
            $this->request_method = $request->method;
        } else if ($request instanceof \yii\console\Request) {
            $this->request_method = 'CLI';
        }

        $this->save(false);
    }

    /**
     * @return bool
     */
    public function finalize()
    {
        $app = Yii::$app;
        $request = $app->request;

        if (!$this->user_id && $request instanceof \yii\web\Request) {
            $this->user_id = Audit::getInstance()->getUserId();
        }

        $this->duration = microtime(true) - YII_BEGIN_TIME;
        $this->memory_max = memory_get_peak_usage();
        return $this->save(false, ['duration', 'memory_max', 'user_id']);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id'             => Yii::t('audit', 'Entry ID'),
            'created'        => Yii::t('audit', 'Created'),
            'ip'             => Yii::t('audit', 'IP'),
            'duration'       => Yii::t('audit', 'Duration'),
            'user_id'        => Yii::t('audit', 'User'),
            'memory_max'     => Yii::t('audit', 'Memory'),
            'request_method' => Yii::t('audit', 'Request Method'),
        ];
    }

    /**
     * @return bool
     */
    public function hasRelatedData()
    {
        if ($this->getLinkedErrors()->count()) {
            return true;
        }
        if ($this->getJavascripts()->count()) {
            return true;
        }
        if ($this->getMails()->count()) {
            return true;
        }
        if ($this->getTrails()->count()) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getUserIP()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return current(array_values(array_filter(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * @return string
     */
    public function getRequestUrl()
    {
        $data = ArrayHelper::getColumn($this->data, 'data');
        if (!isset($data['audit/request']) || !is_array($data['audit/request'])) {
            return Url::to([$this->route ?: '/'], 'https');
        }
        $request = $data['audit/request'];
        $route = $this->route ?: (!empty($request['route']) ? $request['route'] : '/');
        return Url::to(ArrayHelper::merge([$route], $request['GET']), 'https');
    }

}
