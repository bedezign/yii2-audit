<?php

namespace tests\models;

use bedezign\yii2\audit\AuditingBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Post
 *
 * @property integer $id
 * @property string $title
 * @property string $body
 *
 * @mixin AuditingBehavior
 */
class Post extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            AuditingBehavior::className(),
        ];
    }

}
