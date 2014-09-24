# yii2-audit


Yet another auditing module.
This is based on a couple of other projects out there:
 
 * [Sammaye Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail)
 * [Cordernote Audit Module](https://github.com/cornernote/yii-audit-module)


## Features
Installs as a simple module so it can be added without too much hassle.

Once added, this module tracks all incoming pageviews witht he ability to add custom data to a view.

It logs the IP, superglobals ($_GET/$_POST/$_REQUEST


## Installing

* Add a `require` line to your `composer.json`: `'bedezign/yii2-audit: "*"`
* Run the migrations from the `migrations` folder.
* Add a module to your configuration (with optional extra settings) and if it needs to auto trigger, also add it to the bootrap:

    'bootstrap' => ['log', 'auditing', ...],
    'controllerNamespace' => 'frontend\controllers',

    'modules' => [
        'auditing' => [
            'class'             => 'bedezign\yii2\audit\Auditing',
            'ignoreActions'     => 'debug/*',
        ],
    ],
 
This installs the module with auto loading, instructing it to not log anything debug related.

