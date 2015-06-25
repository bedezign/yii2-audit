# Javascript Logging

The module also supports logging of javascript errors, warnings and even regular log entries.
To activate, register the `\bedezign\yii2\audit\web\JSLoggingAsset` in any of your views:

```php
\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
```

This will activate the logger automatically. By default all warnings and errors are transmitted to the backend.

This means that the javascript will need an URL to submit its data to.   
Usually the module can automatically determine what URL to use. 

Should the auto detection fail you can correct the url by updating the setting somewhere in your javascript:

```javascript
window.auditUrl = '/mymodulename/javascript/log';
``` 

(Please also open an issue so we can figure out why the auto-detection failed and hopefully fix it)

## Remarks
* All logging you perform that originates from that page load will be linked to the same entry in the database. If you should need the current entry id for other things, it is added to the `window`-object as `window.auditEntry`.
* If you use ajax or related technologies to load data from the backend, these requests might generate their own audit entries. Any errors that these cause will be linked to that newly created entry. Might be confusing, given that the actual client side logging for these requests will still be linked to the original one.
