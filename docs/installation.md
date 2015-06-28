# Installation and Configuration

## Download

Download using composer by running the following command:

```
$ composer require --prefer-dist bedezign/yii2-audit "*"
```

Or add a `require` line to your `composer.json`: 

```
{
    "require": {
        'bedezign/yii2-audit: "*"
    }
}
```

## Migrations

Run the migrations from the `migrations` folder to create the relevant tables:  

```
$ php yii migrate --migrationPath=@bedezign/yii2/audit/migrations
```

## Configuration

Add a module to your configuration (with optional extra settings) and if it needs to auto trigger, also add it to the bootstrap:

Example:

```php
$config = [
    'modules' => [
        'audit' => 'bedezign\yii2\audit\Audit',
    ],
];
```

See [Module Configuration](module-configuration.md) for the all configuration options and advanced usage information.

## Logging Database Changes

Add `AuditTrailBehavior` to the models you want to log:

```php
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

See [Database Logging](database-logging.md) for the all configuration options and advanced usage information.

## Logging Javascript

Register `JSLoggingAsset` in any of your views:

```php
\bedezign\yii2\audit\web\JSLoggingAsset::register($this);
```

See [Javascript Logging](javascript-logging.md) for the all configuration options and advanced usage information.

## Logging Errors

Add `ErrorHandler` to your configuration array:

```php
$config = [
    'components' => [
        'errorHandler' => [
           'class' => '\bedezign\yii2\audit\components\web\ErrorHandler',
        ],
    ],
];
```

See [Error Logging](error-logging.md) for the all configuration options and advanced usage information.

## Viewing the Audit Data

Assuming you named the module "audit" you can then access the audit module through the following URL:

```
http://localhost/path/to/index.php?r=audit
```

## Where to now ?

Check out the other [Documentation](README.md)
