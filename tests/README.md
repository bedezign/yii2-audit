# Testing

run all tests from this folder - `yii2-audit/tests/`.

## Install Codeception

Install codeception globally

```
composer global require "codeception/codeception=2.0.*"
```

## Helpful Commands

run tests with verbose output

```
~/.composer/vendor/bin/codecept run --debug --fail-fast
```

Build codeception when the config changes

```
~/.composer/vendor/bin/codecept build
```

run a php webserver

```
php -S 0.0.0.0:88 -t codeception/_app/web/
```

## Useful Links

- https://github.com/yiisoft/yii2-codeception
- https://github.com/yiisoft/yii2-app-basic/tree/master/tests
- https://github.com/dektrium/yii2-user/tree/master/tests