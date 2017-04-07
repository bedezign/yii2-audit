# Testing

Audit features 2 different `composer.json` files. The one named `composer.json` is the regular one, for when you include the module in your project. The one named `composer-ci.json` is for continuous integration. It contains a lot more dependencies needed to be able to test everything.

To make sure that the correct one is used, let composer know about it:

```bash
export COMPOSER=composer-ci.json
composer -v install
```

Be sure to do a `composer install` 

run all tests from this folder - `yii2-audit/tests/`.

## Install

Install codeception globally

```bash
composer global require "codeception/codeception=2.2.*"
```

## Build

Build codeception initially, and when the config changes:

```bash
~/.config/composer/vendor/bin/codecept build
```

## Migrate

```bash
php codeception/_app/yii migrate/up --interactive=0
php codeception/_app/yii migrate/up --migrationPath=../src/migrations --interactive=0
```

Note: The default configuration assumes the presence of an `audit_test` database and a user `travis` (no password).


## Helpful Commands

run tests with verbose output

```bash
~/.config/composer/vendor/bin/codecept run --debug --fail-fast
```

run tests with code coverage

```bash
~/.config/composer/vendor/bin/codecept run --coverage-html --coverage-xml
```

run single test

```bash
~/.config/composer/vendor/bin/codecept run functional EntryViewCept.php
```

run a php webserver

```bash
php -S 0.0.0.0:88 -t codeception/_app/web/
```

## Useful Links

- https://github.com/yiisoft/yii2-codeception
- https://github.com/yiisoft/yii2-app-basic/tree/master/tests
- https://github.com/dektrium/yii2-user/tree/master/tests
- https://github.com/bcdennis/laravel-ci-configs/blob/master/codeception-travis-scrutinizer/
