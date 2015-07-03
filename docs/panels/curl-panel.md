# CurlPanel #

This is a speciality panel, designed to help you track data of the cURL requests you make.

The functions provided by this panel are described on the [utility functions page](../utility-functions.md).

## What is logged? ##
By default the panel is configured to log general info regarding the request, the received headers, the connection log and the returned content. If any errors occur it will log both the error number and the error string.

You can disable everything except for the general info by modifying the default panel configuration:

```php
'modules' => [
   'audit' => [
      'panelsMerge' => [
         'audit/curl' => [
            'log' => false,
            'headers' => false,
            'content' => false,
         ]
      ]
   ]
]
```

## Usage ##

Since there is no way to automatically detect when cURL requests are being done, it is up to you to notify the panel.

You can do this by either having the panel trigger the `curl_exec()`-function for you (after you have configured the curl handle), or by calling the begin and end functions before and after you trigger the execution yourself. It depends on what you need, if you use a library and so on.

Both POST data and the original URL cannot be obtained from the cURL handle. You can include them when calling the utility function so that they are logged. If you are certain no redirects will take place, then the URL is not needed. The final URL (the one that actually returns content) *can* be obtained automatically.

Method 1, allow the panel to execute the request:

```php
$url = 'http://the.site.com/';
$post = ['key1' => 'value1', 'key2' => 'value2']; 
$handle = curl_init($url);
... // and other configuration
$result = Audit::getInstance()->curlExec($handle, $url, $post);
``` 

The return result in this case (if you have content tracking enabled or set the `CURLOPT_RETURNTRANSFER` yourself) will be the body of the requested page.

Method 2, in case you have no control over when the request is executed:

```php
$url = 'http://the.site.com/';
$post = ['key1' => 'value1', 'key2' => 'value2']; 
$handle = curl_init($url);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);
... // and other configuration
Audit::getInstance()->curlBegin($handle, $url, $post);
$result = curl_exec($handle);
if (!Audit::getInstance()->curlEnd($handle)) {
   // ... there was an error
}
``` 

Like this, the returned result is a `bool` (true if the request succeeded).

## Beware ##

In order to deliver its awesome functionality, the panel needs to add/configure a couple of options on the handle. This section attempts to provide you with a list so you know exactly what.

### Log ###

The log depends on the verbose functionality. It configures the options `CURLOPT_VERBOSE` and `CURL_STDERR`.

### Headers ###

This uses the `CURLOPT_HEADERFUNCTION` option.

### Content ###

For content tracking the `CURLOPT_RETURNTRANSFER` is enabled.

