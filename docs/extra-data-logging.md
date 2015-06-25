# Extra Data

It is possible to add extra custom data to the current audit entry by simply calling:

```php
\bedezign\yii2\audit\Audit::current()->data('name', 'extra data can be an integer, string, array, object or whatever', 'optional type');
```

Or if you prefer:

```php
\Yii::$app->audit->data(('name', 'extra data can be an integer, string, array, object or whatever', 'optional type');
```
