<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\db\ActiveRecord;
use bedezign\yii2\audit\components\Helper;
use Yii;

/**
 * Class AuditEntry
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
 * @property AuditData[]       $associatedPanels
 */
class AuditEntry extends ActiveRecord
{
    /**
     * @var
     */
    protected $start_time;

    /**
     * @var bool
     */
    protected $autoSerialize = false;

    /**
     * @return string
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
     * @return array
     */
    public function getAssociatedPanels()
    {
        if (!$this->isRelationPopulated('associatedPanels')) {
            $panels = AuditData::findEntryTypes($this->id);
            if (count($this->linkedErrors))
                $panels[] = 'audit/error';

            if (count($this->javascripts))
                $panels[] = 'audit/javascript';

            if (count($this->trails))
                $panels[] = 'audit/trail';

            $this->populateRelation('associatedPanels', $panels);
        }
        $related  = $this->getRelatedRecords();
        return $related['associatedPanels'];
    }

    /**
     * @param $type
     * @return mixed|null
     */
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
     * Returns all linked AuditTrail instances
     * @return AuditTrail[]
     */
    public function getTrails()
    {
        return static::hasMany(AuditTrail::className(), ['entry_id' => 'id']);
    }

    /**
     * Returns all linked AuditJavascript instances
     * @return AuditJavascript[]
     */
    public function getJavascripts()
    {
        return static::hasMany(AuditJavascript::className(), ['entry_id' => 'id']);
    }

    /**
     * @param      $type
     * @param      $data
     * @param bool $compact
     */
    public function addData($type, $data, $compact = true)
    {
        $this->addBatchData([$type => $data], $compact);
    }

    /**
     * @param      $batchData
     * @param bool $compact
     * @throws \yii\db\Exception
     */
    public function addBatchData($batchData, $compact = true)
    {
        $columns = ['entry_id', 'type', 'data'];
        $rows = [];
        foreach ($batchData as $type => $data) {
            $rows[] = [$this->id, $type, Helper::serialize($data, $compact)];
        }
        Yii::$app->db->createCommand()->batchInsert(AuditData::tableName(), $columns, $rows)->execute();
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $app = Yii::$app;
        $request = $app->request;

        $this->route = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        $this->start_time = YII_BEGIN_TIME;

        if ($request instanceof \yii\web\Request) {
            $user = $app->user;
            $this->user_id = $user->isGuest ? 0 : $user->id;
            $this->ip = $request->userIP;
            $this->ajax = $request->isAjax;
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
        $this->duration = microtime(true) - $this->start_time;
        $this->memory_max = memory_get_peak_usage();
        return $this->save(false, ['duration', 'memory_max']);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
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
