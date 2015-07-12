# Testing

run all tests from this folder - `yii2-audit/tests/`.

## Codeception

### Install

Install codeception globally

```
composer global require "codeception/codeception=2.0.*"
```

### Build

Build codeception initially, and when the config changes:

```
~/.composer/vendor/bin/codecept build
```

### Migrate

```
php codeception/_app/yii migrate/up --interactive=0
php codeception/_app/yii migrate/up --migrationPath=../src/migrations --interactive=0
```

Note: The default configuration assumes the presence of an `audit_test` database and a user `travis` (no password).


### Helpful Commands

run tests with verbose output

```
~/.composer/vendor/bin/codecept run --debug --fail-fast
```

run tests with code coverage

```
~/.composer/vendor/bin/codecept run --coverage-html --coverage-xml
```

run single test

```
~/.composer/vendor/bin/codecept run functional EntryViewCept.php
```

run a php webserver

```
php -S 0.0.0.0:88 -t codeception/_app/web/
```

### Useful Links

- https://github.com/yiisoft/yii2-codeception
- https://github.com/yiisoft/yii2-app-basic/tree/master/tests
- https://github.com/dektrium/yii2-user/tree/master/tests
- https://github.com/bcdennis/laravel-ci-configs/blob/master/codeception-travis-scrutinizer/


## Heroku

### Git

Add remote:
```
git remote add heroku https://git.heroku.com/limitless-inlet-7926.git
```

Push changes:
```
git push heroku master
```

### Migrate

```
heroku run php /app/tests/codeception/_app/yii migrate/up --interactive=0
heroku run php /app/tests/codeception/_app/yii migrate/up --migrationPath=/app/src/migrations --interactive=0
```

### Helpful Commands

Interactive shell:
```
heroku run bash
```

Watch logs:
```
heroku logs --tail
```

Set and unset config var:
```
heroku config:set SOME_CONFIG=foobar
heroku config:unset SOME_CONFIG
```

Get config vars:
```
heroku config
```

Database console:
```
apt-get install postgresql-client
heroku pg:psql
```

Reset database:
```
heroku pg:reset DATABASE
```

### Useful Links

- https://devcenter.heroku.com/articles/getting-started-with-php