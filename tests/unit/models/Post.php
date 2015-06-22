<?php

namespace tests\models;

use bedezign\yii2\audit\AuditingBehavior;
use yii\db\ActiveRecord;

/**
 * Post
 *
 * @property integer $id
 * @property string $body
 * @property string $title
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
