# Changelog

## 1.0.2 Under Development

* Bug [#113](https://github.com/bedezign/yii2-audit/issues/113) getting 404 on audit/js-log
* Enh [#112](https://github.com/bedezign/yii2-audit/issues/112): generic cleanup() method in PanelTrait
* Enh [#103](https://github.com/bedezign/yii2-audit/issues/103): Use error hash to determine different messages and files for error filter
* Bug [#99](https://github.com/bedezign/yii2-audit/issues/99): IE8 support for the javascript logger

## 1.0.2 July 14, 2015

* Enh [#61](https://github.com/bedezign/yii2-audit/issues/61): give each panel its own cleanup method
* Enh [#89](https://github.com/bedezign/yii2-audit/issues/89): only show graphs and menu items for active panels
* Enh [#108](https://github.com/bedezign/yii2-audit/issues/108): show IP address in entry grid
* Bug [#107](https://github.com/bedezign/yii2-audit/issues/107): fix logging text or html on non-multipart messages
* helper will always try to uncompress data
* fixes for jslogging that were caused when pdo binary support was added
* fix ordering in Version helper

## 1.0.1 July 12, 2015

* Moved documentation to gh-pages branch
* PostgreSQL compatibility
* Added [heroku](https://limitless-inlet-7926.herokuapp.com/index.php?r=audit) demonstration page
* Enh [#100](https://github.com/bedezign/yii2-audit/issues/100): cURL Panel now detects content types for POST and result. Supported: XML, JSON, query string

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

* initial release
