<?php

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\db\ActiveRecord;
use Yii;

/**
 * AuditTrail
 *
 * @property integer $id
 * @property integer $entry_id
 * @property integer $user_id
 * @property string $action
 * @property string $model
 * @property string $model_id
 * @property string $field
 * @property string $new_value
 * @property string $old_value
 * @property string $created
 *
 * @property AuditEntry $entry
 */
class AuditTrail extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%audit_trail}}';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('audit', 'ID'),
            'entry_id' => Yii::t('audit', 'Entry ID'),
            'user_id' => Yii::t('audit', 'User ID'),
            'action' => Yii::t('audit', 'Action'),
            'model' => Yii::t('audit', 'Type'),
            'model_id' => Yii::t('audit', 'Model ID'),
            'field' => Yii::t('audit', 'Field'),
            'old_value' => Yii::t('audit', 'Old Value'),
            'new_value' => Yii::t('audit', 'New Value'),
            'created' => Yii::t('audit', 'Created'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
        return $this->hasOne(AuditEntry::className(), ['id' => 'entry_id']);
    }

    public static function prettyPrintIfValueIsJson(mixed $value): string
    {
        // Check if data a string. Only if it is string, json decode will work correctly
        if (is_string($value)) {
            // Decode the value and ensure output is an array so we can check its type consistently
            $data = json_decode($value, JSON_OBJECT_AS_ARRAY);
            if (is_array($data)) {
                // Pretty print json by flag for json_encode and ensure line breaks by using print_r
                return print_r(json_encode($data, JSON_PRETTY_PRINT), true);
            }
        }

        return (string)$value;
    }

    /**
     * @return mixed
     */
    public function getDiffHtml()
    {
        if (Audit::getInstance()->enablePrettyDiffForTrails) {
            $oldValue = self::prettyPrintIfValueIsJson((string)$this->old_value);
            $newValue = self::prettyPrintIfValueIsJson((string)$this->new_value);
        } else {
            $oldValue = (string)$this->old_value;
            $newValue = (string)$this->new_value;
        }

        $old = explode("\n", $oldValue);
        $new = explode("\n", $newValue);

        foreach ($old as $i => $line) {
            $old[$i] = rtrim((string)$line, "\r\n");
        }
        foreach ($new as $i => $line) {
            $new[$i] = rtrim((string)$line, "\r\n");
        }

        $diff = new \Diff($old, $new);
        return $diff->render(new \Diff_Renderer_Html_Inline);
    }

    /**
     * @return ActiveRecord|bool
     */
    public function getParent()
    {
        $parentModel = new $this->model;
        $parent = $parentModel::findOne($this->model_id);
        return $parent ? $parent : $parentModel;
    }

}
