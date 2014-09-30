<?php
/**
 * Model to store error information
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category
 * @copyright 2014 B&E DeZign
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Helper;

/**
 * Class AuditError
 * @package bedezign\yii2\audit\models
 *
 * @property int    $id
 * @property int    $audit_id
 * @property string $created
 * @property string $message
 * @property int    $code
 * @property string $file
 * @property int    $line
 * @property mixed  $trace
 */
class AuditError extends AuditModel
{
    protected $serializeAttributes = ['trace'];

    public function setEntry(AuditEntry $entry)
    {
        $this->audit_id = $entry->id;
    }

    public static function log(AuditEntry $entry, $exception)
    {
        $error = new static();
        $error->entry = $entry;
        $error->record($exception);
        return $error->save(false) ? $error : null;
    }

    public function record(\Exception $exception)
    {
        $this->message  = $exception->getMessage();
        $this->code     = $exception->getCode();
        $this->file     = $exception->getFile();
        $this->line     = $exception->getLine();
        $this->trace    = Helper::cleanupTrace($exception->getTrace());
    }

    public function attributeLabels()
    {
        return
        [
            'code'  => 'Error Code'
        ];
    }


}