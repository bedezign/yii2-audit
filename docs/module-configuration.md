# Module Configuration

Word of caution: The module is configured by default to only allow viewing access to users with the role 'admin'. This functionality is only available in Yii if you have enabled [RBAC](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac) (via the `authManager`-component). If not, please set this option to `null`. If you do so you should consider activating the `accessUsers`-option, you don't want to give everyone access to your audit data!


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
