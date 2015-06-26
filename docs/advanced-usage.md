# Advanced Usage

Word of caution: The module is configured by default to only allow viewing access to users with the role 'admin'. This functionality is only available in Yii if you have enabled [RBAC](http://www.yiiframework.com/doc-2.0/guide-security-authorization.html#role-based-access-control-rbac) (via the `authManager`-component). If not, please set this option to `null`. If you do so you should consider activating the `accessUsers`-option, you don't want to give everyone access to your audit data!


## Additional options

```php
'modules' => [
    'audit' => [
        'class' => 'bedezign\yii2\audit\Audit',
        'db' => 'db', // Name of the component to use for database access
        'trackActions' => ['*'], // List of actions to track. '*' is allowed as the last character to use as wildcard
        'ignoreActions' => ['audit/*', 'debug/*'], // Actions to ignore. '*' is allowed as the last character to use as wildcard (eg 'debug/*')
        'maxAge' => 'debug', // Maximum age (in days) of the audit entries before they are truncated
        'accessIps' => ['127.0.0.1', '192.168.*'], // IP address or list of IP addresses with access to the viewer, null for everyone (if the IP matches)
        'accessRoles' => ['admin'], // Role or list of roles with access to the viewer, null for everyone (if the user matches)
        'accessUsers' => [1, 2], // User ID or list of user IDs with access to the viewer, null for everyone (if the role matches)
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
