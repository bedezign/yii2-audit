# Module Configuration

Word of caution: The module is configured by default to only allow viewing access to users with the role 'admin'. This functionality is only available in Yii if you have enabled [RBAC](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac) (via the `authManager`-component). If not, please set this option to `null`. If you do so you should consider activating either the `accessUsers`- or the `accessIps`-option, you don't want to give everyone access to your audit data!


## Audit Module Options

```php
'modules' => [
    'audit' => [
        'class' => 'bedezign\yii2\audit\Audit',
        // the layout that should be applied for views within this module
        'layout' => 'main',
        // Name of the component to use for database access
        'db' => 'db', 
        // List of actions to track. '*' is allowed as the last character to use as wildcard
        'trackActions' => ['*'], 
        // Actions to ignore. '*' is allowed as the last character to use as wildcard (eg 'debug/*')
        'ignoreActions' => ['audit/*', 'debug/*'],
        // Maximum age (in days) of the audit entries before they are truncated
        'maxAge' => 'debug',
        // IP address or list of IP addresses with access to the viewer, null for everyone (if the IP matches)
        'accessIps' => ['127.0.0.1', '192.168.*'], 
        // Role or list of roles with access to the viewer, null for everyone (if the user matches)
        'accessRoles' => ['admin'],
        // User ID or list of user IDs with access to the viewer, null for everyone (if the role matches)
        'accessUsers' => [1, 2],
        // Compress extra data generated or just keep in text? For people who don't like binary data in the DB
        'compressData' => true,
        // The callback to use to convert a user id into an identifier (username, email, ...). Can also be html.
        'userIdentifierCallback' => ['app\models\User', 'userIdentifierCallback'],
        // If the value is a simple string, it is the identifier of an internal to activate (with default settings)
        // If the entry is a '<key>' => '<string>|<array>' it is a new panel. It can optionally override a core panel or add a new one.
        'panels' => [
            'audit/request',
            'audit/error',
            'audit/trail',
            'app/views' => [
                'class' => 'app\panels\ViewsPanel',
                // ...
            ],
        ],
        'panelsMerge' => [
           // ... merge data (see below)
        ]
    ],
],
```

## Selective Logging

If you only want to log the database changes, javascript and errors you should use the following module setting. Standard pageview logging will be ignored.

```php
'modules' => [
    'audit' => [
        'class' => 'bedezign\yii2\audit\Audit',
        'ignoreActions' => ['*'],
    ],
],
```

## Panel configuration ##

You have the choice between either using `$panels` to specify a complete list of panels and their configuration, or using `$panelsMerge` for a selective update. 

The `$panels`-variable accepts a number of different formats:
* A simple string (no key) to load a core panel. For example `"audit/config"`
* A string index with a string value. This adds a new panel with the give class: `"app/views" => "app\panels\ViewPanel"` will result in a new panel of class `app\panels\ViewPanel` being loaded
* A string index with an array value. If the index is the name of a core panel then the core class will be added, so no need for that. If it is a custom panel you'll need to specify the entire configuration, including `class`.

### Smaller changes via merging ###

The module also provides a `$panelsMerge` configuration option.  
This allows you to simply specify what you like to change, as compared to the current `$panels` configuration.

The panels updated and/or added via this configuration are considered part of the default panel configuration and will be initialised during the logging phaze of the module.

**A couple examples:**

```php
'panelsMerge' => [
   "audit/config" => []
]
```

This results in the config (core) panel being loaded as well without having to re-specify the entire panels list.


```php
'panelsMerge' => [
   "audit/curl" => ['log' => false]
]
```

This disables the verbose log for the cURL panel

```php
'panelsMerge' => [
	'app/views' => [
		'class' => 'app\panels\ViewsPanel',
	]
]
```

This adds a completely new panel.
