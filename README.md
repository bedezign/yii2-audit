# Yii2 Audit

[![Join Chat](https://img.shields.io/badge/gitter-join%20chat-blue.svg?style=flat-square)](https://gitter.im/bedezign/yii2-audit?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Latest Version](https://img.shields.io/github/tag/bedezign/yii2-audit.svg?style=flat-square&label=release)](https://github.com/bedezign/yii2-audit/tags)
[![Software License](https://img.shields.io/badge/license-BSD-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/bedezign/yii2-audit/master.svg?style=flat-square)](https://travis-ci.org/bedezign/yii2-audit)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/bedezign/yii2-audit.svg?style=flat-square)](https://scrutinizer-ci.com/g/bedezign/yii2-audit)
[![Total Downloads](https://img.shields.io/packagist/dt/bedezign/yii2-audit.svg?style=flat-square)](https://packagist.org/packages/bedezign/yii2-audit)

Yii2 Audit Module.
This is based on a couple of other projects out there:

 * [Sammaye Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail)
 * [Cornernote Audit Module](https://github.com/cornernote/yii-audit-module)

## Features
Installs as a simple module so it can be added without too much hassle.

* Tracks all incoming pageviews with the ability to add custom data to a view.
  It logs the user-id (if any), IP, URL/route/referrer/redirect, superglobals (`$_GET`/`$_POST`/`$_SERVER`/`$_FILES`/`$_COOKIES`), request/response headers, memory usage and request duration.  You can either track specific actions and nothing else or exclude specific routes from logging (wildcard supported).

* Track database changes. By implementing the `AuditTrailBehavior` this is easily realized thanks to a modified version of [Sammayes Yii2 Audit Trail](https://github.com/Sammaye/yii2-audittrail).

* Automatically log javascript errors. Errors and warning are logged automatically (if you activate the functionality), but the javascript component also provides methods to manually add logging entries.

* View your data. The module contains a nice viewer that is automatically made available when you add it to your configuration. It has configurable permissions to limit access to this functionality, both by roles or by user-id.

## Installation and Documentation

Refer to the [Documentation](docs/)

## Screenshots

![Index example](docs/screenshots/audit-index.png?raw=true)
![View example](docs/screenshots/audit-view.png?raw=true)
![Diff example](docs/screenshots/audit-diff.png?raw=true)
