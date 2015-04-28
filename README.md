# yii2-audit


Yet another auditing module.
This is based on a couple of other projects out there:

 * [Sammaye Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail)
 * [Cordernote Audit Module](https://github.com/cornernote/yii-audit-module)

## Features
Installs as a simple module so it can be added without too much hassle.

Once added, this module tracks all incoming pageviews with the ability to add custom data to a view.

It logs the user-id (if any), IP, superglobals ($_GET/$_POST/$_SERVER/$_FILES/$_COOKIES), memory usage, referrer and origin.

Additionally it can save database changes thanks to a modified version of [Sammayes Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail).

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

If you also want errors to be logged, you have to register the included errorhandler as well in you configuration:

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

## Usage

You can then access the auditing module through the following URL:

http://localhost/path/to/index.php?r=auditing/request/index

### Screenshots

![Index example](docs/screenshots/audit-index.png?raw=true)
![View example](docs/screenshots/audit-view.png?raw=true)