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
'bootstrap' => ['log', 'audit', ...],
'controllerNamespace' => 'frontend\controllers',

'modules' => [
    'audit' => [
        'class' => 'bedezign\yii2\audit\Audit',
        // ... see advanced-options.md
    ],
],
```

## Viewing the Audit Data

Assuming you named the module "audit" you can then access the audit module through the following URL:

```
http://localhost/path/to/index.php?r=audit
```
