# Heroku

## Git

Add remote:
```
git remote add heroku https://git.heroku.com/yii2-audit.git
```

Push changes:
```
git push heroku master
```

## Migrate

```
heroku run php /app/tests/codeception/_app/yii migrate/up --interactive=0
heroku run php /app/tests/codeception/_app/yii migrate/up --migrationPath=/app/src/migrations --interactive=0
```

## Helpful Commands

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

## Useful Links

- https://devcenter.heroku.com/articles/getting-started-with-php