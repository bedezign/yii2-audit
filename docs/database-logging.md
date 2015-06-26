# Database Logging

If you want database changes to be logged, you have to add the `AuditTrailBehavior` to the models you want to log.

## Basic Configuration

```php
/**
 * Post
 * @mixin AuditTrailBehavior
 */
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
}
```

## Advanced Configuration

```php
/**
 * Post
 * @mixin AuditTrailBehavior
 */
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'AuditTrailBehavior' => [
                'class' => 'bedezign\yii2\audit\AuditTrailBehavior',
                // Array with fields to save. You don't need to configure both `allowed` and `ignored`
                'allowed' => ['some_field'],
                // Array with fields to ignore. You don't need to configure both `allowed` and `ignored`
                'ignored' => ['another_field'],
                // Array with classes to ignore
                'ignoredClasses' => ['common\models\Model'],
                // Skip fields where bouth old and new values are NULL
                'skipNulls' => false,
                // Is the behavior is active or not
                'active' => true,
                // Model attribute to use to get the user_id from an attribute in the owner model
                // Set to null to get the user_id from `Yii::$app->user->id`
                'userAttribute' => 'user_id',
                // Date format to use in stamp - set to "Y-m-d H:i:s" for datetime or "U" for timestamp
                'dateFormat' => 'Y-m-d H:i:s',
            ]
        ];
    }
}
```
