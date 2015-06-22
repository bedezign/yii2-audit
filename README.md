# yii2-audit


Yet another auditing module.
This is based on a couple of other projects out there:

 * [Sammaye Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail)
 * [Cornernote Audit Module](https://github.com/cornernote/yii-audit-module)

## Features
Installs as a simple module so it can be added without too much hassle.

* Tracks all incoming pageviews with the ability to add custom data to a view.
  It logs the user-id (if any), IP, superglobals ($_GET/$_POST/$_SERVER/$_FILES/$_COOKIES), memory usage, referrer and origin. You can either track specific actions and nothing else or exclude specific routes from logging (wildcard supported).

* Track database changes. By implementing the `AuditingBehavior` this is easily realized thanks to a modified version of [Sammayes Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail).

* Automatically log javascript errors. Errors and warning are logged automatically (if you activate the functionality), but the javascript component also provides methods to manually add logging entries.

* View your data. The module contains a nice viewer that is automatically made available when you add it to your configuration. It has configurable permissions to limit access to this functionality.

## Installing

* Run `composer.phar require --prefer-dist bedezign/yii2-audit "*"` or add a `require` line to your `composer.json`: `'bedezign/yii2-audit: "*"`
* Run the migrations from the `migrations` folder. `yii migrate --migrationPath=@bedezign/yii2/audit/migrations`
* Add a module to your configuration (with optional extra settings) and if it needs to auto trigger, also add it to the bootrap.

Example:

    'bootstrap' => ['log', 'auditing', ...],
    'controllerNamespace' => 'frontend\controllers',

    'modules' => [
        'auditing' => [
            'class' => 'bedezign\yii2\audit\Auditing',
            'ignoreActions' => 'debug/*',
        ],
    ],

This installs the module with auto loading, instructing it to not log anything debug related.

#### Additional options

    'modules' => [
        'auditing' => [
            'class' => 'bedezign\yii2\audit\Auditing',
            'db' => 'db', // Name of the component to use for database access
            'trackActions' => ['*'], // List of actions to track. '*' is allowed as the last character to use as wildcard
            'ignoreActions' => 'debug/*', // Actions to ignore. '*' is allowed as the last character to use as wildcard (eg 'debug/*')
            'truncateChance' => 75, // Chance in % that the truncate operation will run, false to not run at all
            'maxAge' => 'debug', // Maximum age (in days) of the audit entries before they are truncated
            'accessUsers' => [1, 2], // (List of) user(s) IDs with access to the viewer, null for everyone (if the role matches)
            'accessRoles' => ['admin'], // (List of) role(s) with access to the viewer, null for everyone (if the user matches)
        ],
    ],

### Error Logging

If you want errors to be logged, you have to register the included errorhandler as well in you configuration:

    'errorHandler' => [
       'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
       'errorAction' => 'site/error',
    ],

### Database changes

If you want database changes to be logged, you have to add the `AuditingBehavior` to the models you want to log.

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditingBehavior'
        ];
    }

#### Additional options

    public function behaviors()
    {
        return [
            'LoggableBehavior' => [
                'class' => 'sammaye\audittrail\LoggableBehavior',
                'allowed' => ['some_field'], // Array with fields to save. You don't need to configure both `allowed` and `ignored`
                'ignored' => ['another_field'], // Array with fields to ignore. You don't need to configure both `allowed` and `ignored`
                'ignoredClasses' => ['common\models\Model'], // Array with classes to ignore
                'skipNulls' => false, // Skip fields where bouth old and new values are NULL
                'active' => true // Is the behavior is active or not
            ]
        ];
    }

#### Only log database changes

If you only want to log the database changes you should use the following module setting. All pageview logging will be ignored.

    'modules' => [
        'auditing' => [
            'class' => 'bedezign\yii2\audit\Auditing',
            'ignoreActions' => ['*'],
        ],
    ],

There is a grid for only database changes available at:

http://localhost/path/to/index.php?r=auditing/default/trail

### Javascript Logging

The module also supports logging of javascript errors, warnings and even regular log entries.
To activate, register the `assets\JSLoggingAsset` in any of your views:

     \bedezign\yii2\audit\assets\JSLoggingAsset::register($this);

This will activate the logger automatically. By default all warnings and errors are transmitted to the backend.

The default configuration assumes that the module was added as "auditing" (so the log url would be "*/auditing/javascript/log*"). If that is not the case, please make sure to update the setting somewhere in your javascript:

    window.jsLogger.logUrl = '/mymodulename/javascript/log';

All javascript logging will be linked to the entry associated with the page entry created when you performed the initial request. This is accomplished by adding the ID of that entry in the `window`-object (`window.auditEntry`).

Beware: If you use ajax or related technologies to load data from the backend, these requests might generate their own auditing entries. If those cause backend errors they will be linked to that new entry. This might be a bit weird with the javascript logging being linked to the older entry.

### Extra Data

It is possible to add extra custom data to the current audit entry by simply calling:

    \bedezign\yii2\audit\Auditing::current()->data('name', 'extra data can be an integer, string, array, object or whatever', 'optional type');

Or if you prefer:

    \Yii::$app->auditing->data(('name', 'extra data can be an integer, string, array, object or whatever', 'optional type');

### Render AuditEntry.id in Layout

It is often useful for users to be able to report the AuditEntry.id to the developer.  To render the ID to the page include the partial provided:

```php
<?= $this->render('@vendor/bedezign/yii2-audit/views/_audit_entry_id', [
  'link' => false, // set to true to render the id as a link
]); ?>
```

## Viewing the audit data

Assuming you named the module "auditing" you can then access the auditing module through the following URL:

    http://localhost/path/to/index.php?r=auditing

If you would like to see all database changes individually you can access:

    http://localhost/path/to/index.php?r=auditing/default/trail


### Screenshots

![Index example](docs/screenshots/audit-index.png?raw=true)
![View example](docs/screenshots/audit-view.png?raw=true)
![Diff example](docs/screenshots/audit-diff.png?raw=true)
