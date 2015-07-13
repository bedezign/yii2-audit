<?php

namespace tests\app\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use yii\db\ActiveRecord;

/**
 * Post
 *
 * @property integer $id
 * @property string $body
 * @property string $title
 *
 * @mixin AuditTrailBehavior
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
            'audit' => ['class' => AuditTrailBehavior::className()],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'body'], 'required'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

}
