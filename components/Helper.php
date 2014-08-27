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
     * Convert the given value into a gzip compressed string so it can be stored in the database
     * @param mixed $data
     * @param bool  $compact        true to call the {@link compact()} function first
     * @return string
     */
    public static function serialize($data, $compact = true)
    {
        if ($compact)
            $data = static::compact($data);

        return gzcompress(serialize($data));
    }

    public static function unserialize($data)
    {
        return unserialize(gzuncompress($data));
    }

    /**
     * Enumerate an
     * @param $data
     * @param int $threshold
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
}