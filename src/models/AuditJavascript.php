<?php
/**
 * This model allows for storing of javascript logging entries linked to a specific audit entry
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\components\Helper;

/**
 * AuditJavascript
 *
 * @package bedezign\yii2\audit\models
 * @property int    $id
 * @property int    $entry_id
 * @property string $created
 * @property string $message
 * @property string $origin
 * @property string $data
 */
class AuditJavascript extends AuditModel
{
    /**
     * @param AuditEntry $entry
     */
    public function setEntry(AuditEntry $entry)
    {
        $this->entry_id = $entry->id;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['entry_id', 'message'], 'required']
        ];
    }

    public static function tableName()
    {
        return '{{audit_javascript}}';
    }
}