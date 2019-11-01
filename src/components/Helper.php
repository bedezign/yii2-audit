<?php
/**
 * Provides a number of helper functions for the audit component
 */

namespace bedezign\yii2\audit\components;

use bedezign\yii2\audit\Audit;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * Helper
 * @package bedezign\yii2\audit\components
 */
class Helper extends \yii\base\BaseObject
{
    /**
     * Convert the given value into a gzip compressed blob so it can be stored in the database
     * @param mixed $data
     * @param bool $compact true to call the {@link compact()} function first
     * @return string               binary blob of data
     */
    public static function serialize($data, $compact = true)
    {
        if ($compact)
            $data = self::compact($data);

        $data = serialize($data);
        $data = self::compress($data);

        return $data;
    }

    /**
     * Re-inflates and unserializes a blob of compressed data
     * @param string $data
     * @return mixed            false if an error occurred
     */
    public static function unserialize($data)
    {
        if (is_resource($data))
            // For some databases (like Postgres) binary columns return as a resource, fetch the content first
            $data = stream_get_contents($data, -1, 0);

        $originalData = $data;
        $data = self::uncompress($data);

        if ($data === false)
            $data = $originalData;

        $data = @unserialize($data);
        if ($data === false)
            $data = $originalData;

        return $data;
    }

    /**
     * Compress
     * @param mixed $data
     * @return string binary blob of data
     */
    public static function compress($data)
    {
        if (Audit::getInstance()->compressData)
            $data = gzcompress($data);
        return $data;
    }

    /**
     * Compress
     * @param mixed $data
     * @return string binary blob of data
     */
    public static function uncompress($data)
    {
        $originalData = $data;
        $data = @gzuncompress($data);
        return $data ?: $originalData;
    }

    /**
     * Enumerate an array and get rid of the values that would exceed the $threshold size when serialized
     * @param array $data Non-array data will be converted to an array
     * @param bool $simplify If true, replace single valued arrays by just its value.
     * @param int $threshold serialized size to use as maximum
     * @return array
     */
    public static function compact($data, $simplify = false, $threshold = 512)
    {
        $data = ArrayHelper::toArray($data);
        if ($simplify)
            array_walk($data, function (&$value) {
                if (is_array($value) && count($value) == 1) $value = reset($value);
            });

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
     * Generate a stacktrace and clean it (usually for regular errors)
     * @param int $skip Amount of entries to skip (usually 1 or 2). 2 is assuming this helper function and your logging function
     * @return array
     */
    public static function generateTrace($skip = 2)
    {
        $trace = debug_backtrace();
        array_pop($trace); // remove the last trace since it would be the entry script, not very useful
        if ($skip > 0)
            $trace = array_slice($trace, $skip);
        return self::cleanupTrace($trace);
    }

    /**
     * Normalize a stack trace so that all entries have the same keys and cleanup the arguments (removes anything that
     * cannot be serialized).
     * @param array $trace
     * @return array
     */
    public static function cleanupTrace($trace)
    {
        if (!is_array($trace))
            return [];

        foreach ($trace as $n => $call) {
            $call['file'] = isset($call['file']) ? $call['file'] : 'unknown';
            $call['line'] = isset($call['line']) ? $call['line'] : 0;
            $call['function'] = isset($call['function']) ? $call['function'] : 'unknown';
            $call['file'] = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $call['file']);

            // XDebug
            if (isset($call['params'])) unset($call['params']);

            // Trace entry contains the class instance, also compact and include this
            if (isset($call['object']))
                $call['object'] = current(self::cleanupTraceArguments([$call['object']]));

            if (isset($call['args']))
                $call['args'] = self::cleanupTraceArguments($call['args']);

            $trace[$n] = $call;
        }

        return $trace;
    }

    /**
     * Cleans up the given data so it can be serialized
     * @param     $args
     * @param int $recurseDepth Amount of recursion cycles before we start replacing data with "Array" etc
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
                    $object = self::cleanupTraceArguments(ArrayHelper::toArray($value, [], false), $recurseDepth - 1);
                    $object['__class'] = $class;
                    $args[$name] = $object;
                }
            } else if (is_array($value)) {
                if ($recurseDepth > 0)
                    $args[$name] = self::cleanupTraceArguments($value, $recurseDepth - 1);
                else
                    $args[$name] = 'Array';
            }
        }
        return $args;
    }

    /**
     * Hash a long string to a short string.
     * @link http://au1.php.net/crc32#111931
     *
     * @param $data
     * @return string
     */
    public static function hash($data)
    {
        static $map = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $hash = bcadd(sprintf('%u', crc32($data)), 0x100000000);
        $str = '';
        do {
            $str = $map[bcmod($hash, 62)] . $str;
            $hash = bcdiv($hash, 62);
        } while ($hash >= 1);
        return $str;
    }

    /**
     * If the data resembles a query string it will be returned as a formatted variable for output
     * @param $data
     * @return null|string
     */
    public static function formatAsQuery($data)
    {
        $data = rawurldecode($data);
        if (!preg_match('/^([\w\d\-\[\]]+(=[^&]*)?(&[\w\d\-\[\]]+(=[^&]*)?)*)?$/', $data))
            return null;

        $result = [];
        parse_str($data, $result);
        return VarDumper::dumpAsString($result, 15);
    }

    /**
     * If the data contains JSON it will be returned as a pretty printable string
     * @param $data
     * @return null|string
     */
    public static function formatAsJSON($data)
    {
        $decoded = @json_decode($data);
        return $decoded ? json_encode($decoded, JSON_PRETTY_PRINT) : null;
    }

    /**
     * If the data contains XML it will be returned as a pretty printable string
     * @param $data
     * @return null|string
     */
    public static function formatAsXML($data)
    {
        $doc = new \DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        if (@$doc->loadXML($data))
            return htmlentities($doc->saveXML(), ENT_COMPAT, 'UTF-8');
        return null;
    }

    /**
     * If the data contains HTML it will be returned as a pretty printable string
     * @param $data
     * @return null|string
     */
    public static function formatAsHTML($data)
    {
        if ($data == strip_tags($data) || strtolower(substr(ltrim($data), 0, 5)) == '<?xml')
            return null;

        $doc = new \DOMDocument('1.0');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        if (@$doc->loadHTML($data))
            return htmlentities($doc->saveHTML(), ENT_COMPAT, 'UTF-8');
        return null;
    }
}