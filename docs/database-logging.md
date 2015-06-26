# Database Logging

If you want database changes to be logged, you have to add the `AuditTrailBehavior` to the models you want to log.

## Basic Configuration

```php
public function behaviors()
{
    return [
        'bedezign\yii2\audit\AuditTrailBehavior'
    ];
}
```

## Advanced Configuration

```php
public function behaviors()
{
    return [
        'AuditTrailBehavior' => [
            'class' => 'bedezign\yii2\audit\AuditTrailBehavior',
            'allowed' => ['some_field'], // Array with fields to save. You don't need to configure both `allowed` and `ignored`
            'ignored' => ['another_field'], // Array with fields to ignore. You don't need to configure both `allowed` and `ignored`
            'ignoredClasses' => ['common\models\Model'], // Array with classes to ignore
            'skipNulls' => false, // Skip fields where bouth old and new values are NULL
            'active' => true // Is the behavior is active or not
        ]
    ];
}
```
