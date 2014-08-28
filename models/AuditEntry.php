<?php
/**
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package
 * @category
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Auditing;
use bedezign\yii2\audit\components\Helper;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
 * @property string $session
 * @property string $url
 * @property string $route
 * @property string $data           Compressed data collection of everything incoming
 * @property int    $memory
 * @property int    $memory_max
 */
class AuditEntry extends ActiveRecord
{
    public static function getDb()
    {
        return Auditing::current() ? Auditing::current()->getDb() : parent::getDb();
    }

    public static function tableName()
    {
        return '{{%audit_entry}}';
    }

    public function beforeSave($insert)
    {
        if ($insert)
            $this->created = new Expression('NOW()');

        $this->data = Helper::serialize($this->data, false);
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->data = Helper::unserialize($this->data);
    }

    public static function create($initialise = true)
    {
        $entry = new static;
        if ($initialise)
            $entry->record();

        return $entry;
    }

    /**
     * Records the current application state into the instance.
     */
    public function record()
    {
        $dataMap = ['get' => $_GET, 'post' => $_POST,
            'cookies' => $_COOKIE, 'env' => $_SERVER, 'files' => $_FILES];

        $app                = \Yii::$app;
        $user               = $app->user;
        $request            = $app->request;

        $this->user_id      = $user->isGuest ? 0 : $app->user->id;
        $this->route        = $app->requestedAction ? $app->requestedAction->uniqueId : null;
        $this->start_time   = YII_BEGIN_TIME;

        if ($request instanceof \yii\web\Request) {
            $this->url      = $request->url;
            $this->ip       = $request->userIP;
            $this->referrer = $request->referrer;
            $this->origin   = $request->headers->get('location');

            if ($app->has('session')) {
                $this->session  = $app->session->id;
                $dataMap['session'] = $_SESSION;
            }
        }
        else if ($request instanceof \yii\console\Request) {
            // Add extra link, makes it easier to detect
            $dataMap['params'] = $request->params;
            $this->url         = $request->scriptFile;
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
}