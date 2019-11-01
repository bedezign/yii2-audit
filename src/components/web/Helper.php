<?php

namespace bedezign\yii2\audit\components\web;

/**
 * Web Helper
 * @package bedezign\yii2\audit\components\web
 */
class Helper extends \yii\base\BaseObject
{
    protected static $_bootstrapNamespace = null;

    public static function setBootstrapNamespace($namespace)
    {
        self::$_bootstrapNamespace = $namespace;
    }

    public static function bootstrapAsset()
    {
        return sprintf('%s\\BootstrapAsset', static::getBootstrapNamespace());
    }

    protected static function getBootstrapNamespace()
    {
        if (self::$_bootstrapNamespace === null) {
            $checkNamespaces = ['\yii\bootstrap', '\yii\bootstrap4'];
            foreach ($checkNamespaces as $namespace) {
                if (class_exists("$namespace\\BootstrapAsset")) {
                    self::$_bootstrapNamespace = $namespace;
                }
            }
        }
        if (self::$_bootstrapNamespace === null) {
            throw new \RuntimeException("Unable to determine the bootstrap version");
        }
        return self::$_bootstrapNamespace;
    }

    public static function bootstrap($class, $function, ...$parameters)
    {
        $class = sprintf("%s\\$class", static::getBootstrapNamespace());
        return call_user_func_array([$class, $function], $parameters);
    }
}