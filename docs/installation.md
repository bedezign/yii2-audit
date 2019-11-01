---
layout: default
title: Installation and Configuration
permalink: /docs/installation/
---

# Installation and Configuration

## Download

Download using composer by running the following command:

```
$ composer require --prefer-dist bedezign/yii2-audit:"^1.0"
```

Or add a `require` line to your `composer.json`: 

```
{
    "require": {
        "bedezign/yii2-audit": "^1.0"
    }
}
```

## Migrations

Audit uses Yii's [namespaced migrations](https://www.yiiframework.com/doc/guide/2.0/en/db-migrations#namespaced-migrations), this means you'll
have to update the CLI configuration (or whatever configuration is used when you run the migration tool) with the namespace path for the module.

As described on Yii's configuration page, you need to add our namespace to the `migrationNamespaces`.

```
  'controllerMap' => [
    'migrate' => [
      'migrationNamespaces' => [
        # Other migration namespaces
          'bedezign\yii2\audit\migrations',
      ],
  ],
```  
If you then run the migration tool it should detect audits' migrations: 

```
$ ./yii migrate
Yii Migration Tool (based on Yii v2.0.30-dev)

Total 8 new migrations to be applied:
	bedezign\yii2\audit\migrations\m150626_000001_create_audit_entry
	bedezign\yii2\audit\migrations\m150626_000002_create_audit_data
	bedezign\yii2\audit\migrations\m150626_000003_create_audit_error
	bedezign\yii2\audit\migrations\m150626_000004_create_audit_trail
	bedezign\yii2\audit\migrations\m150626_000005_create_audit_javascript
	bedezign\yii2\audit\migrations\m150626_000006_create_audit_mail
	bedezign\yii2\audit\migrations\m150714_000001_alter_audit_data
	bedezign\yii2\audit\migrations\m170126_000001_alter_audit_mail

Apply the above migrations? (yes|no) [no]:
```

Upgrading from pre 1.0? [Be sure to read this](../upgrading-0.1-0.2/).

## Module Configuration

Add `Audit` to your configuration array:

```php
<?php
$config = [
    'modules' => [
        'audit' => 'bedezign\yii2\audit\Audit',
    ],
];
```

See [Module Configuration](../module-configuration/) for the all configuration options and advanced usage information.

## Logging Database Changes

Add `AuditTrailBehavior` to the models you want to log:

```php
<?php
class Post extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }
}
```

See [Trail Panel](../trail-panel/) for the all configuration options and advanced usage information.

You can also attach `AuditTrailBehavior` to models eg. from `vendor`

```
// attach audit trails to 3rd-party models
$trailedModels = [
    \pheme\settings\models\Setting::class,
    \Da\User\Model\User::class,
    \Da\User\Model\Profile::class
];
foreach ($trailedModels as $model) {
    \yii\base\Event::on(
        $model,
        \yii\db\ActiveRecord::EVENT_INIT,
        function ($event) {
            $event->sender->attachBehavior('audit', \bedezign\yii2\audit\AuditTrailBehavior::class);
        }
    );
}
```

## Logging Javascript

Register `JSLoggingAsset` in any of your views:

```php
<?php
\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
```

See [Javascript Panel](../javascript-panel/) for the all configuration options and advanced usage information.

## Logging Errors

Add `ErrorHandler` to your configuration array:

```php
<?php
$config = [
    'components' => [
        'errorHandler' => [
            // web error handler
            'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
            // console error handler
            //'class' => '\bedezign\yii2\audit\components\console\ErrorHandler',
        ],
    ],
];
```

**Important:** Be sure to use the correct error handler! Don't simply add it to the `common` configuration, but instead add it in the frontend/web and `console` configuration separately.  

See [Error Panel](../error-panel/) for the all configuration options and advanced usage information.

## Viewing the Audit Data

Assuming you named the module "audit" you can then access the audit module through the following URL:

```
http://localhost/path/to/index.php?r=audit
```

## Where to now ?

Check out the other [Documentation](../)

