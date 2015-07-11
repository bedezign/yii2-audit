# Yii2 Audit

[![Join Chat](https://img.shields.io/badge/gitter-join%20chat-blue.svg?style=flat-square)](https://gitter.im/bedezign/yii2-audit?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Version](https://img.shields.io/github/tag/bedezign/yii2-audit.svg?style=flat-square&label=release)](https://github.com/bedezign/yii2-audit/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bedezign/yii2-audit/master.svg?style=flat-square)](https://travis-ci.org/bedezign/yii2-audit)
[![HHVM](https://img.shields.io/hhvm/bedezign/yii2-audit.svg?style=flat-square)](http://hhvm.h4cc.de/package/bedezign/yii2-audit)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit)
[![Total Downloads](https://img.shields.io/packagist/dt/bedezign/yii2-audit.svg?style=flat-square)](https://packagist.org/packages/bedezign/yii2-audit)
[![Yii2 Framework](https://img.shields.io/badge/extension-Yii2_Framework-green.svg?style=flat-square)](http://www.yiiframework.com/extension/yii2-audit)

Yii2 Audit is a module that records and displays web/cli requests, database changes, php/js errors and associated data.

## Features

* Tracks minimal data in the base entry:
  * `user_id` - User ID of the visitor (if any), based on `Yii::$app->user->id`.
  * `ip` - IP Address of the visitor.
  * `request_method` - The method used to generate the request, eg: `CLI` for console requests and `GET`, `POST`, `DELETE`, `PUT`, `PATCH`, `OPTIONS` or `HEAD` for web requests.
  * `ajax` - If the page was requested using ajax.
  * `route` - The controller and action of the request.
  * `duration` - How long the request took to serve.
  * `memory_max` - The peak memory usage during the request.
  * `created` - The datetime the entry was created.

* Tracks additional data using configurable Panels.  Each panel is optional, and you can even create your own.
  * `RequestPanel` - Tracks all incoming web and console request data:
    * URL Information including the route and params.
    * PHP SuperGlobals `$_GET`, `$_POST`, `$_SERVER`, `$_FILES` and `$_COOKIES`.
    * Headers from the Request and Response.
  * `AssetPanel` - Asset Bundles loaded for the request.
  * `ConfigPanel` - Yii and PHP configuration that was used for the request.
  * `DbPanel` - SQL queries.
  * `ErrorPanel` - Record all PHP exceptions and errors in the background.  Once logged you can configure a cron task to email the errors to a developer so issues can be fixed before they are even reported by a user.
  * `JavascriptPanel` - Automatically log JavaScript errors. Errors and warning are logged automatically by including `JSLoggingAsset` asset bundle.  The javascript component also provides methods to manually add logging entries.
  * `LogPanel` - Yii logs.
  * `MailPanel` - Emails that were sent during the request
  * `ProfilingPanel` - Application profiling information.
  * `TrailPanel` - Database changes that were made during the request using the `AuditTrailBehavior`.
  * `ExtraDataPanel` - Extra data that you want to store.
  * `CurlPanel` - Track your applications' cURL requests (and their replies, log and headers)
  * `YourOwnPanel` - Create your own panel to capture any data you want.

* Installs as a simple module so it can be added without any hassle.

* You can either track specific actions and nothing else or exclude specific routes from logging (wildcard supported).

* View your data. The module contains a nice viewer that is automatically made available when you add it to your configuration. It has configurable permissions to limit access to this functionality by IPs, roles or users.


## Documentation

Getting started? Try the [Installation Guide](docs/installation.md).  You will find further information in the [Documentation](docs/README.md).

For changes since the last version see the [Changelog](CHANGELOG.md).

## Screenshots

### Dashboard
![Dashboard](https://cloud.githubusercontent.com/assets/51875/8369827/b70355ee-1bfe-11e5-9748-dd864f0500de.png)

### Entry View
![Audit Entry View](https://cloud.githubusercontent.com/assets/51875/8395061/3b004aca-1d97-11e5-8b71-6787c662ea3e.png)

#### Trail Panel
![Trail Panel](https://cloud.githubusercontent.com/assets/51875/8372048/7f4f86de-1c1e-11e5-91a5-7052b597992f.png)

#### Database Panel
![Database Panel](https://cloud.githubusercontent.com/assets/51875/8395068/94b25018-1d97-11e5-9857-a7d3e151cc97.png)

#### Log Panel
![Log Panel](https://cloud.githubusercontent.com/assets/51875/8395070/af005528-1d97-11e5-8629-0a4fb3f9b4dd.png)

#### Profiling Panel
![Profiling Panel](https://cloud.githubusercontent.com/assets/51875/8395072/cc95d2a2-1d97-11e5-891e-05580d03fd7a.png)

#### Error Panel
![Error Panel](https://cloud.githubusercontent.com/assets/51875/8395074/f9b1d196-1d97-11e5-874d-829c095aead8.png)

#### Javascript Panel
![Javascript Panel](https://cloud.githubusercontent.com/assets/51875/8395077/25f75e88-1d98-11e5-9ade-32a5e9b16511.png)


## Contributing

Contributions are welcome.  Please refer to the [contributing guidelines](CONTRIBUTING.md).

Thanks to [everyone who has contributed](CREDITS.md).


## Project Resources

* [GitHub Project](https://github.com/bedezign/yii2-audit)
* [Yii2 Extension](http://www.yiiframework.com/extension/yii2-audit)
* [Packagist Package](https://packagist.org/packages/bedezign/yii2-audit)
* [Travis CI Testing](https://travis-ci.org/bedezign/yii2-audit)
* [Scrutinizer CI Code Quality](https://scrutinizer-ci.com/g/bedezign/yii2-audit)


## License

BSD-3 - Please refer to the [license](LICENSE.md).
