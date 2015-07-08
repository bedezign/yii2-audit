# Utility functions #

The audit-module allows panels to link extra functions via its `registerFunction()`-function.
Some of the panels add methods during their initialisation that are then available during the application cycle.

__Note__: These functions are only available if the panel is actually added to the `$panels`-list (or to the `$panelsMerge`). 

Usage is simply:

```php
$module = Audit::getInstance();
$module->theFunction(relevantArguments);
```

(Or you can skip the adding a variable first and call the function directly on `getInstace()`)

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

## CurlPanel ##
Depending on how you normally do your cURL requests you can use this panel in 2 different ways:

```php
... // Create and configure curl handle
$curlResult = $module->curlExec($curlHandle, $startingUrl = null, $postData = null);
```

Or

```php
... // Create and configure curl handle
$module->curlBegin($curlHandle, $startingUrl = null, $postData = null);
curl_exec($curlHandle);
$module->curlEnd($curlHandle);
```

For more information about the cURL-panel, [see here](panels/curl-panel.md)