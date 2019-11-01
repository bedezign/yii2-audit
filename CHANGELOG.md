# Changelog

## 1.1.2 November 1, 2019

So finally a release. 
It's long overdue, I know. Trust me, there are reasons for my absence, but I'll skip the excuses part and promise to try to do better from now on, even though it's impossible to give any guarantee.
There's tons more to do (like getting tests to work again etc), I wanted to get a new release out there first so everyone can go back to a stable version.

This module is now PHP 7.2 and Yii 2 2.0.13+ only. 

* Bug [#270](https://github.com/bedezign/yii2-audit/issues/270): Make panels compatible with yii2-debug 2.1.6 and later
* Enh [#267](https://github.com/bedezign/yii2-audit/issues/267): Use Yii2's namespaced migrations functionality
* Bug [#263](https://github.com/bedezign/yii2-audit/issues/263): fix bs4 issues from yii2-debug
* Bug [#261](https://github.com/bedezign/yii2-audit/issues/262): Fix "group by" exception on SQL Server  
* Bug [#246](https://github.com/bedezign/yii2-audit/issues/246): fix when args missing from error trace
* Enh [#264](https://github.com/bedezign/yii2-audit/issues/264): show the whole request url in entry view
* Enh [#259](https://github.com/bedezign/yii2-audit/issues/259): French translation
* Enh [#252](https://github.com/bedezign/yii2-audit/issues/252): Allow manual entry definition via `setEntry` function
* Enh [#251](https://github.com/bedezign/yii2-audit/issues/251): Override Module::getInstance() to allow for derived Audit module classes
* Enh [#250](https://github.com/bedezign/yii2-audit/issues/250): Added 'renderEmail' panel function detection to allow for custom email renderers (should still be tested, sorry, no data atm)

## 1.1.1 February 4, 2018

* Enh [#225](https://github.com/bedezign/yii2-audit/issues/225): German translation
* Enh [#236](https://github.com/bedezign/yii2-audit/pull/236): Chinese translation
* Bug [#221](https://github.com/bedezign/yii2-audit/issues/221): Verify component existence in the console, terminate execution with more flair if not.

## 1.1.0 January 20, 2018

* Enh [#231](https://github.com/bedezign/yii2-audit/issues/231): This release is identical to 1.0.13 except that **it requires Yii 2.0.13** to make it compatible with PHP 7.2. It will still work on older PHP versions.

## 1.0.13 January 20, 2018

* Bug [#216](https://github.com/bedezign/yii2-audit/issues/216): Migration failed for MSSQL on alter_audit_mail
* Enh [#219](https://github.com/bedezign/yii2-audit/pull/219): Refactored chart panels
* Enh: Use `HTTP_X_FORWARDED_FOR` as IP when it is set
* Enh [#217](https://github.com/bedezign/yii2-audit/pull/217), [#218](https://github.com/bedezign/yii2-audit/pull/218): Optimized database queries for panels
* Enh: Made `php-mime-mail-parser` optional (mail panel will now work without, with less functionality). This means you don't need the `mailparse` extension anymore to install Audit.
* Bug [#233](https://github.com/bedezign/yii2-audit/issues/233): Made DbPanel work with versions that have the [duplicate queries count](https://github.com/yiisoft/yii2-debug/pull/293)
* A new version will be released shortly (1.1.0) that will be PHP >= 7.2 and  Yii >= 2.0.13. As of PHP 7.2 `object` is reserved as a type hint keyword and thus the Yii `Object` class was renamed to `BaseObject`. For PHP < 7.2 a workaround was added that still provides an `Object` class. This means that Audit v1.0 will work for Yii >= 2.0.13, as long as you do not use PHP >= 7.2


## 1.0.12 August 27, 2017

* Enh [#188](https://github.com/bedezign/yii2-audit/issues/188): Ignore attributes when they contain arrays
* Enh [#191](https://github.com/bedezign/yii2-audit/issues/191): add an option to disable most common timestamp fields
* Bug [#204](https://github.com/bedezign/yii2-audit/pull/204): Fixed typo in Turkisch translations
* Enh: Now using PhpMimeMailParser to display the email HTML
* Enh: Improved IP detection for users behind a proxy
* Enh [#214](https://github.com/bedezign/yii2-audit/issues/191): Reduced amount of queries to display the main screen

## 1.0.11 April 15, 2017

* Bug: use correct database when running migrations if the database component is not "db"
* Bug: check if the module has been installed (prevents errors prior to migration while installing)

## 1.0.10 March 26, 2017

* Bug [#201](https://github.com/bedezign/yii2-audit/issues/201): Fix migration on pgsql

## 1.0.9 March 25, 2017

* Enh [#200](https://github.com/bedezign/yii2-audit/issues/200): fix ID column name duplicating
* Bug [#199](https://github.com/bedezign/yii2-audit/issues/199): Fix user id filtering
* Enh [#195](https://github.com/bedezign/yii2-audit/issues/195): cleanup solo AuditEntry records
* Enh [#194](https://github.com/bedezign/yii2-audit/issues/194): add ignoreKeys to RequestPanel to lower data usage
* Enh [#193](https://github.com/bedezign/yii2-audit/issues/193): charts in panels shows "undefined"
* Enh [#189](https://github.com/bedezign/yii2-audit/issues/189): enhance MailPanel
* Bug [#187](https://github.com/bedezign/yii2-audit/issues/187): audit_mail table data too long
* Enh [#185](https://github.com/bedezign/yii2-audit/issues/185): log config
* Enh [#182](https://github.com/bedezign/yii2-audit/issues/182): Added ability to save overrided field with another name
* Bug [#184](https://github.com/bedezign/yii2-audit/issues/184): Use full url for js log action
* Bug [#180](https://github.com/bedezign/yii2-audit/issues/180): Fix dbPanel errors
* Enh [#174](https://github.com/bedezign/yii2-audit/issues/174): Ability to use custom user id instead Yii::$app->user->id

## 1.0.8 November 11, 2016

* Bug [#172](https://github.com/bedezign/yii2-audit/issues/172): Declaration of getUrl() compatible with yii\debug\Panel::getUrl()
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
