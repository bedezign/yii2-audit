# Extra Data Panel

It is possible to add extra custom data to the current audit entry by simply calling:

```php
use bedezign\yii2\audit\Audit;

$key = 'type or identifier';
$data = 'extra data can be an integer, string, array, object or whatever';
Audit::getInstance()->data($key, $data);
```

Alternatively you can achieve the same via the application instance (as always in the docs, assuming the module is added as `audit`):

```php
\Yii::$app->getModule('audit')->data($key, $data);
```

The `data()`-function is a utilitairy function that is linked to the module by the `ExtraData`-panel.
For an overview of all other available built-in functions, please take a look [here](../utility-functions.md)