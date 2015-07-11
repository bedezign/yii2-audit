---
layout: default
title: Documentation
permalink: /docs/
---

# Documentation

Yii2 Audit tracks each hit to your application (via an web or console entry script, eg `web/index.php` or `yii`) and stores this as an `AuditEntry`.

A `Panel` is a class responsible for capturing and displaying data.  Panel data is given an `entry_id` and is related to an `AuditEntry`.  This data is displayed in detail within the entry view page and may also have its own views and dashboards.

## Configuration

- [Installation and Configuration](installation/)
- [Module Configuration](module-configuration/)
- [Utility Functions](utility-functions/)

## Panels

- [Creating a Custom Panel to Log Rendered Views](custom-views-panel/)
- [Database](database-panel/)
- [Javascript](javascript-panel/)
- [Error](error-panel/)
- [Extra Data](extra-data-panel/)
- [cURL](curl-panel/)

## Viewing Data

- [Replacing user_id](replacing-user_id/)
- [Application Views](application-views/)

## Upgrading

- [Upgrading from 0.1.x to 0.2? Definitely read this!](upgrading-0.1-0.2/)
