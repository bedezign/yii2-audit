# Extra Data

It is possible to add extra custom data to the current audit entry by simply calling:

```php
\bedezign\yii2\audit\Audit::current()->data('type or identifier', 'extra data can be an integer, string, array, object or whatever');
```

Or if you prefer:

```php
\Yii::$app->audit->data(('type or identifier', 'extra data can be an integer, string, array, object or whatever');
```
