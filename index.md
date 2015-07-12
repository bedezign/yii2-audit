---
layout: default
title: Yii2 Audit
description: Yii2 Audit records and displays web/cli requests, database changes, php/js errors and associated data.
jumbotron: true
---

[![Join Chat](https://img.shields.io/badge/gitter-join%20chat-blue.svg?style=flat-square)](https://gitter.im/bedezign/yii2-audit?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Version](https://img.shields.io/github/tag/bedezign/yii2-audit.svg?style=flat-square&label=release)](https://github.com/bedezign/yii2-audit/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](https://bedezign.github.io/yii2-audit/license/)
[![Build Status](https://img.shields.io/travis/bedezign/yii2-audit/master.svg?style=flat-square)](https://travis-ci.org/bedezign/yii2-audit)
[![HHVM](https://img.shields.io/hhvm/bedezign/yii2-audit.svg?style=flat-square)](http://hhvm.h4cc.de/package/bedezign/yii2-audit)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit)
[![Total Downloads](https://img.shields.io/packagist/dt/bedezign/yii2-audit.svg?style=flat-square)](https://packagist.org/packages/bedezign/yii2-audit)
[![Yii2 Framework](https://img.shields.io/badge/extension-Yii2_Framework-green.svg?style=flat-square)](http://www.yiiframework.com/extension/yii2-audit)

## Features

### Minimal Footprint

Tracks minimal data in the base entry:

* `user_id` - User ID of the visitor (if any), based on `Yii::$app->user->id`.
* `ip` - IP Address of the visitor.
* `request_method` - The method used to generate the request, eg: `CLI` for console requests and `GET`, `POST`, `DELETE`, `PUT`, `PATCH`, `OPTIONS` or `HEAD` for web requests.
* `ajax` - If the page was requested using ajax.
* `route` - The controller and action of the request.
* `duration` - How long the request took to serve.
* `memory_max` - The peak memory usage during the request.
* `created` - The datetime the entry was created.

### Tracks additional data using configurable Panels.

Each panel is optional, and you can even create your own.

* `RequestPanel` - Tracks all incoming web and console request data:
  * URL Information including the route and params.
  * PHP SuperGlobals `$_GET`, `$_POST`, `$_SERVER`, `$_FILES` and `$_COOKIES`.
  * Headers from the Request and Response.
* `AssetPanel` - Asset Bundles loaded for the request.
* `ConfigPanel` - Yii and PHP configuration that was used for the request.
* `DbPanel` - SQL queries.
* `ErrorPanel` - Record all PHP exceptions and errors in the background.  Once logged you can configure a cron task to email the errors to a developer so issues can be fixed before they are even reported by a user. [more info](https://bedezign.github.io/yii2-audit/docs/error-panel/)
* `JavascriptPanel` - Automatically log JavaScript errors. Errors and warning are logged automatically by including `JSLoggingAsset` asset bundle.  The javascript component also provides methods to manually add logging entries. [more info](https://bedezign.github.io/yii2-audit/docs/javascript-panel/)
* `LogPanel` - Yii logs.
* `MailPanel` - Emails that were sent during the request. [more info](https://bedezign.github.io/yii2-audit/docs/mail-panel/)
* `ProfilingPanel` - Application profiling information.
* `TrailPanel` - Database changes that were made during the request using the `AuditTrailBehavior`. [more info](https://bedezign.github.io/yii2-audit/docs/trail-panel/)
* `ExtraDataPanel` - Extra data that you want to store. [more info](https://bedezign.github.io/yii2-audit/docs/extra-data-panel/)
* `CurlPanel` - Track your applications cURL requests (including replies, log and headers) [more info](https://bedezign.github.io/yii2-audit/docs/curl-panel/)
* `YourOwnPanel` - Create your own panel to capture any data you want. [more info](https://bedezign.github.io/yii2-audit/docs/custom-views-panel/)

### Powerful, yet Simple

* Installs as a simple module so it can be added without any hassle.
* You can either track specific actions and nothing else or exclude specific routes from logging (wildcard supported).
* View your data. The module contains a nice viewer that is automatically made available when you add it to your configuration. It has configurable permissions to limit access to this functionality by IPs, roles or users.

## Documentation

Getting started? Try the [Installation Guide](https://bedezign.github.io/yii2-audit/docs/installation/).  You will find further information in the [Documentation](https://bedezign.github.io/yii2-audit/docs/).

For changes since the last version see the [Changelog](https://github.com/bedezign/yii2-audit/blob/master/CHANGELOG.md).

## Screenshots

<div class="row thumbnails">
    {% for screenshot in site.data.screenshots limit:4 %}
    <div class="col-md-3">
        <h3>{{screenshot.name}}</h3>
        <div class="thumbnail">
            <a href="{{screenshot.url}}" class="fancybox" rel="screenshots"><img src="{{screenshot.url}}" alt="{{screenshot.name}}"></a>
        </div>
    </div>
    {% endfor %}
</div>

More images are available from the [Screenshots](https://bedezign.github.io/yii2-audit/screenshots/) page.

## Contributing

Contributions are welcome.  Please refer to the [contributing guidelines](https://github.com/bedezign/yii2-audit/blob/master/CONTRIBUTING.md).

Thanks to [everyone who has contributed](https://bedezign.github.io/yii2-audit/credits/).


## Project Resources

* [Project Homepage](https://bedezign.github.io/yii2-audit/)
* [GitHub Project](https://github.com/bedezign/yii2-audit)
* [Yii2 Extension](http://www.yiiframework.com/extension/yii2-audit)
* [Packagist Package](https://packagist.org/packages/bedezign/yii2-audit)
* [Travis CI Testing](https://travis-ci.org/bedezign/yii2-audit)
* [Scrutinizer CI Code Quality](https://scrutinizer-ci.com/g/bedezign/yii2-audit)


## License

BSD-3 - Please refer to the [license](https://bedezign.github.io/yii2-audit/license/).
