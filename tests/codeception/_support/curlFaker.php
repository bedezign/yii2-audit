<?php
/**
 * This file (which must be included manually since it sits in the wrong namespace) essentially overrides
 * the global namespaced curl functions and allows you to redirect them to whatever callback you please.
 * Just use curl_callback('curl_function_name_to_override', $closure)
 */

namespace {
    function curl_callback($function, $callback)
    {
        \Yii::$app->params[$function] = $callback;
    }
}

namespace bedezign\yii2\audit\panels {

    function curl_exec($resource)
    {
        return curl_dummy('curl_exec', $resource);
    }

    function curl_setopt($resource, $option, $value)
    {
        return curl_dummy('curl_setopt', $resource, $option, $value);
    }

    function curl_getinfo($resource, $opt = null)
    {
        return curl_dummy('curl_getinfo', $resource, $opt);
    }

    function curl_error ($resource)
    {
        return curl_dummy('curl_error', $resource);
    }

    function curl_errno ($resource)
    {
        return curl_dummy('curl_errno', $resource);
    }

    function curl_multi_getcontent($resource)
    {
        return curl_dummy('curl_multi_getcontent', $resource);
    }

    function curl_dummy($function)
    {
        $parameters = func_get_args();
        array_shift($parameters);
        if (isset(\Yii::$app->params[$function]))
            return call_user_func_array(\Yii::$app->params[$function], $parameters);
        else
            return call_user_func_array('\\' . $function, $parameters);
    }
}