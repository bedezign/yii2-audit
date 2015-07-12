# Changelog

## 1.0.1 July 12, 2015

* Moved documentation to github.io
* PostgreSQL compatibility
* Added [heroku](https://limitless-inlet-7926.herokuapp.com/index.php?r=audit) demonstration page
* Enh #100: cURL Panel now detects content types for POST and result. Supported: XML, JSON, query string

## 1.0.0 July 9, 2015

* ability to undelete and roll back to any version of a model
* added controllers and views for entry, trail, javascript and errors
* added graphs to main page
* created views that can be included in your application
* audit_entry is created on demand for trails, javascript and errors
* user callback to display custom output instead of user_id
* extendable panel based logging
* added extensive documentation
* improved access control and added IP based access
* no longer storing url data in entry table
* created fresh migration scripts
* added functionality to email errors via cron script
* added unit test suite
* huge code overhaul

## 0.1.0

* initial relase
