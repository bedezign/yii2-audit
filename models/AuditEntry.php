<?php
/**
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package
 * @category
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Auditing;
use bedezign\yii2\audit\components\Helper;
use yii\db\ActiveRecord;

/**
 * Class AuditEntry
 * @package bedezign\yii2\audit\models
 *
 * @property int    $id
 * @property int    $user_id        0 means anonymous
 * @property string $ip
 * @property string $referrer
 * @property string $origin
 * @property string $session
 * @property string $url
 * @property string $route
 * @property string $data           Compressed data collection of everything incoming
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

    public static function create($initialise = true, Auditing $manager = null)
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
        $dataMap = ['session' => $_SESSION, 'get' => $_GET, 'post' => $_POST,
            'cookies' => $_COOKIE, 'env' => $_SERVER, 'files' => $_FILES];

        $app            = \Yii::$app;
        $user           = $app->user;
        $request        = $app->request;

        $this->user_id  = $user->isGuest ? 0 : $app->user->id;
        $this->session  = $app->session->id;
        $this->route    = $app->requestedAction ? $app->requestedAction->uniqueId : null;

        if ($request instanceof \yii\web\Request) {
            $this->url      = $request->url;
            $this->ip       = $request->userIP;
            $this->referrer = $request->referrer;
            $this->origin   = $request->headers->get('location');
        }
        else if ($request instanceof \yii\console\Request) {
            // Add extra link, makes it easier to detect
            $dataMap['params'] = $request->params;
            $this->url         = $request->scriptFile;
        }

        // Record the incoming data
        $this->data = [];
        foreach ($dataMap as $index => $values)
            if (count($values))
                $this->data[$index] = Helper::compact($values);
    }
}