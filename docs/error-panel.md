---
layout: default
title: Error Panel
permalink: /docs/error-panel/
---

# Error Panel

## Basic Configuration

If you want errors to be logged, you have to register the included errorhandler as well in you configuration:

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

If your project is based upon `yii2-app-basic` you'll want to add the web error handler to your `web.php` file and the console error handler to `console.php`. 

Similarly for the `yii2-app-advanced` you would use `frontend/config/main.php` for the web error handler and `console/config/main.php` for the console error handler.

Don't just simply add it to the generic configuration (eg `common/config/main.php` for advanced). You will trigger extra errors if you do and you might lose vital logging information.

## Emailing Errors

A command is available to email errors which can be added to your cron. 

```
php yii audit/error-email
```

You should ensure you have setup a `mailer` component and have a `scriptUrl` property in the `urlManager` component in your console configuration. In addition you need a `supportEmail` in your `params` setting.

For example:

```php
<?php
$console = [
    'params' => [
        'supportEmail' => 'errors@example.com',
    ],
    'components' => [
        'mailer' => [
            // see http://www.yiiframework.com/doc-2.0/guide-tutorial-mailing.html
            'class' => 'yii\swiftmailer\Mailer',
        ],
        'urlManager' => [
            // required because the CLI script doesn't know the URL
            'scriptUrl' => 'http://example.com/',
        ],
    ],
]
```

