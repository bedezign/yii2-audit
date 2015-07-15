---
layout: default
title: Cleanup Data
permalink: /docs/cleanup-data/
---

# Cleanup Data

Audit data can get very large, very quickly.  Audit comes bundled with a command to help you manage the data.

## Command Usage

A command is available to cleanup old data, which can be added to your cron. 

```
php yii audit/cleanup [panels] [maxAge]
```

Cleanup all data based on the Module and Panel settings:

```
php yii audit/cleanup
```

Cleanup all data by specifing `0` as the max age:

```
php yii audit/cleanup all 0
```

Cleanup all data older than 30 days:

```
php yii audit/cleanup all 30
```

Cleanup only data from LogPanel and ProfilingPanel older than a week

```
php yii audit/cleanup audit/log,audit/profiling 7
```

## Max Age Settings

In addition to being able to specify the `maxAge` as an argument to the command, each panel has a `maxAge` 
property which specifies the number of days to keep the data.  By default this is `NULL`, which means data 
will never be removed.

```php
<?php
$config = [
    'modules' => [
        'audit' => [
            'class' => 'bedezign\yii2\audit\Audit',
            'maxAge' => 30, // 30 days
            'panels' => [
                'audit/log' => [
                    'maxAge' => 7, // 7 days
                ],
            ],
        ],
    ],
];
```

## Screenshot

![Cleanup Command](https://cloud.githubusercontent.com/assets/51875/8689333/83f1e17a-2ae5-11e5-8a8a-10ef30f23fdb.png)
