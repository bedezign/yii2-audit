<?php
/**
 * This model allows for storing of javascript logging entries linked to a specific audit entry
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Helper;

/**
 * Class AuditJavascript
 * @package bedezign\yii2\audit\models
 * @property int    $id
 * @property int    $audit_id
 * @property string $created
 * @property string $message
 * @property string $origin
 * @property string $data
 */
class AuditJavascript extends AuditModel
{
    public function setEntry(AuditEntry $entry)
    {
        $this->audit_id = $entry->id;
    }

    public function rules()
    {
        return [
            [['audit_id', 'message'], 'required']
        ];
    }
}