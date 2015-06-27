# Extra Data

It is possible to add extra custom data to the current audit entry by simply calling:

```php
use bedezign\yii2\audit\Audit;

Audit::current()->data('type or identifier', 'extra data can be an integer, string, array, object or whatever');
```
