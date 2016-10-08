# Changelog

## 1.0.8 UNRELEASED

* Enh [#171](https://github.com/bedezign/yii2-audit/issues/171): Override to save related record label instead of id in AuditTrail
* Bug [#168](https://github.com/bedezign/yii2-audit/issues/168): On certain errors the ProfilePanel data is not saved 
* Enh [#167](https://github.com/bedezign/yii2-audit/issues/167): Get parent model for audit trail

## 1.0.7 July 27, 2016
* Bug [#162](https://github.com/bedezign/yii2-audit/issues/162): fix charts 
* Bug [#160](https://github.com/bedezign/yii2-audit/issues/160): error when config is setup but tables are not present
* Bug [#145](https://github.com/bedezign/yii2-audit/issues/145): Bootstrap Array to String conversion fix
* Bug [#153](https://github.com/bedezign/yii2-audit/issues/153): save user_id on finalize function

## 1.0.6 March 21, 2016
* Bug [#141](https://github.com/bedezign/yii2-audit/issues/141): PHP7: ErrorPanel should support Throwable
* Enh [#140](https://github.com/bedezign/yii2-audit/issues/140): `AuditTrailSearch.field` should allow `array`
* Enh [#138](https://github.com/bedezign/yii2-audit/issues/138): Allow `*` as prefix in route matching
* Bug: LogPanels without messages should still work

## 1.0.5 October 28, 2015
* Enh: Added `generateTrace()`-function to the `Helper` for external usage
* Enh: ErrorPanel now avoids duplicate exception logging
* Enh: Javascript logging sets the type to 'unknown' if none was specified
* Couple minor things fixed (tablenames and comments, thanks Evgeniy and Tobias)

## 1.0.4 August 11, 2015

* Enh [#114](https://github.com/bedezign/yii2-audit/issues/114): Cleanup command should show counts
* Bug [#115](https://github.com/bedezign/yii2-audit/issues/115): heroku trail page not working
* Bug [#116](https://github.com/bedezign/yii2-audit/issues/116): js log/info not working
* Enh [#118](https://github.com/bedezign/yii2-audit/issues/118): cleanup should use options instead of params
* Bug [#122](https://github.com/bedezign/yii2-audit/issues/122): Migration tool throws SQL error in MySQL database with UTF8
* POST data recognition improved
* SOAP Panel now logs all exceptions during Soap Calls, not just `SoapFault`s

## 1.0.3 July 29, 2015

* Bug [#113](https://github.com/bedezign/yii2-audit/issues/113): getting 404 on audit/js-log
* Enh [#112](https://github.com/bedezign/yii2-audit/issues/112): generic cleanup() method in PanelTrait
* Enh [#103](https://github.com/bedezign/yii2-audit/issues/103): Use error hash to determine different messages and files for error filter
* Bug [#99](https://github.com/bedezign/yii2-audit/issues/99): IE8 support for the javascript logger
* Enh [#64](https://github.com/bedezign/yii2-audit/issues/64): Support for SOAP
* Bug [#116](https://github.com/bedezign/yii2-audit/issues/116): js log/info not working
* Enh [#114](https://github.com/bedezign/yii2-audit/issues/114): Cleanup should show counts
* Enh [#110](https://github.com/bedezign/yii2-audit/issues/110): All model saving data methods should be in the panels
* Enh [#118](https://github.com/bedezign/yii2-audit/issues/118): Cleanup should use options instead of parameters
* Enh [#119](https://github.com/bedezign/yii2-audit/issues/119): Heroku should showcase all panels

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
