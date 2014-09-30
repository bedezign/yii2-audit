<?php
/**
 * Provides a number of helper functions for the auditing component
 *
 * @author    Steve Guns <steve@bedezign.com>
 * @package   com.bedezign.yii2.audit
 * @category  components
 * @copyright 2014 B&E DeZign
 */


namespace bedezign\yii2\audit\components;

use yii\base\Object;
use yii\helpers\ArrayHelper;

class Helper extends Object
{
    /**
     * Convert the given value into a gzip compressed blob so it can be stored in the database
     * @param mixed $data
     * @param bool  $compact        true to call the {@link compact()} function first
     * @return string               binary blob of data
     */
    public static function serialize($data, $compact = true)
    {
        if ($compact)
            $data = static::compact($data);

        return gzcompress(serialize($data));
    }

    /**
     * Re-inflates and unserializes a blob of compressed data
     * @param string $data
     * @return mixed            false if an error occurred
     */
    public static function unserialize($data)
    {
        return unserialize(gzuncompress($data));
    }

    /**
     * Enumerate an array and get rid of the values that would exceed the $threshold size when serialized
     * @param array     $data               Non-array data will be converted to an array
     * @param int       $threshold          serialized size to use as maximum
     * @return array
     */
    public static function compact($data, $threshold = 512)
    {
        $data = ArrayHelper::toArray($data);

        $tooBig = [];
        foreach ($data as $index => $value)
            if (strlen(serialize($value)) > $threshold)
                $tooBig[] = $index;

        if (count($tooBig)) {
            $data = array_diff_key($data, array_flip($tooBig));
            $data['__removed'] = $tooBig;
        }

        return $data;
    }

    /**
     * Normalize a stack trace so that all entries have the same keys and cleanup the arguments (removes anything that
     * cannot be serialized).
     * @param array     $trace
     * @return array
     */
    public static function cleanupTrace($trace)
    {
        foreach ($trace as $n => $call) {
            $trace[$n]['file'] = isset($call['file']) ? $call['file'] : 'unknown';
            $trace[$n]['line'] = isset($call['line']) ? $call['line'] : 0;
            $trace[$n]['function'] = isset($call['function']) ? $call['function'] : 'unknown';
            $trace[$n]['file'] = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $trace[$n]['file']);

            // XDebug
            if (isset($trace[$n]['params'])) unset($trace[$n]['params']);

            if (isset($trace[$n]['args']))
                $trace[$n]['args'] = static::cleanupTraceArguments($trace[$n]['args']);
        }

        return $trace;
    }

    /**
     * Cleans up the given data so it can be serialized
     * @param $args
     * @param int $recurseDepth     Amount of recursion cycles before we start replacing data with "Array" etc
     * @return mixed
     */
    public static function cleanupTraceArguments($args, $recurseDepth = 3)
    {
        foreach ($args as $name => $value) {
            if (is_object($value)) {
                $class = get_class($value);
                // By default we just mention the object type
                $args[$name] = 'Object(' . $class . ')';

                if ($recurseDepth > 0) {
                    // Make sure to limit the toArray to non recursive, it's much to easy to get stuck in an infinite loop
                    $object = static::cleanupTraceArguments(ArrayHelper::toArray($value, [], false), $recurseDepth - 1);
                    $object['__class'] = $class;
                    $args[$name] = $object;
                }
            }
            else if (is_array($value)) {
                if ($recurseDepth > 0)
                    $args[$name] = static::cleanupTraceArguments($value, $recurseDepth - 1);
                else
                    $args[$name] = 'Array';
            }
        }
        return $args;
    }

    /**
     * Converts a value to a string to output. This was taken from the Yii2 Debug component
     * @param mixed $value
     * @return string
     */
    public static function formatValue($value)
    {
        return htmlspecialchars(\yii\helpers\VarDumper::dumpAsString($value), ENT_QUOTES|ENT_SUBSTITUTE, \Yii::$app->charset, true);
    }
}