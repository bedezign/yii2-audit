<?php
/**
 * Base model for the audit classes containing some helper functions to auto serialize/unserialize the
 * raw data attributes.
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\components\Helper;

class AuditModel extends \yii\db\ActiveRecord
{
    /** @var bool                   If true, automatically pack and unpack the data attribute */
    protected $autoSerialize        = true;
    protected $serializeAttributes  = ['data'];

    public static function getDb()
    {
        return Audit::current() ? Audit::current()->getDb() : parent::getDb();
    }

    public function beforeSave($insert)
    {
        if ($insert && $this->hasAttribute('created'))
            $this->created = new \yii\db\Expression('NOW()');

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute= Helper::serialize($this->$attribute, false);

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute = Helper::unserialize($this->$attribute);
    }

    public function afterFind()
    {
        parent::afterFind();

        if ($this->autoSerialize)
            foreach ($this->serializeAttributes as $attribute)
                if ($this->hasAttribute($attribute))
                    $this->$attribute = Helper::unserialize($this->$attribute);
    }
}