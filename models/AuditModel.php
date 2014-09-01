<?php
/**
 * Base model for the audit classes
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category
 * @copyright 2014 B&E DeZign
 */

namespace bedezign\yii2\audit\models;

use bedezign\yii2\audit\Auditing;
use bedezign\yii2\audit\components\Helper;
use yii\db\Expression;

class AuditModel extends \yii\db\ActiveRecord
{
    /** @var bool                   If true, automatically pack and unpack the data attribute */
    protected $autoSerialize        = true;
    protected $serializeAttributes  = ['data'];

    public static function getDb()
    {
        return Auditing::current() ? Auditing::current()->getDb() : parent::getDb();
    }

    public function beforeSave($insert)
    {
        if ($insert && $this->hasAttribute('created'))
            $this->created = new Expression('NOW()');


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