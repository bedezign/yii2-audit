# Javascript Logging

The module also supports logging of javascript errors, warnings and even regular log entries.
To activate, register the `\bedezign\yii2\audit\web\JSLoggingAsset` in any of your views:

```php
\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
```

This will activate the logger automatically. By default all warnings and errors are transmitted to the backend.

The default configuration assumes that the module was added as "audit" (so the log url would be "*/audit/javascript/log*"). If that is not the case, please make sure to update the setting somewhere in your javascript:

```javascript
window.jsLogger.logUrl = '/mymodulename/javascript/log';
```

All javascript logging will be linked to the entry associated with the page entry created when you performed the initial request. This is accomplished by adding the ID of that entry in the `window`-object (`window.auditEntry`).

Beware: If you use ajax or related technologies to load data from the backend, these requests might generate their own audit entries. If those cause backend errors they will be linked to that new entry. This might be a bit weird with the javascript logging being linked to the older entry.
