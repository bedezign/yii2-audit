# Utility functions #

The audit-module allows panels to link extra functions via its `registerFunction()`-function.
Some of the panels add methods during their initialisation that are then available during the application cycle.

Usage is simply:

```php
\Yii::$app->getModule('audit')->theFunction(relevantArguments);
```

## ExtraDataPanel ##
This panel adds a `data()`-function to link custom data to the entry.

```php
$module->data($type, $data);
```

`$type` is usually a string, but it depends on what you need. `$data` is mixed.

## ErrorPanel ##
The error panel provides 2 shortcut functions to log exceptions or messages to the entry:

```php
$module->exception(Exception $exception)
```

This creates a new entry (if needed) and logs the `$exception` into it, including stack trace.
It actually links to `AuditError::log()` but takes care of the `AuditEntry` parameter for you.


```php
$module->errorMessage($message, $code = 0, $file = '', $line = 0, $trace = []);
```

Logs an error message into your entry. Same as `exception()`, but it links to `AuditError::logMessage()`.