<?php

namespace bedezign\yii2\audit\components;

use yii\db\ActiveRecordInterface;
use yii\db\Expression;

class DbHelper
{
    /**
     * Returns like operator depending on db driver
     *
     * @param string|ActiveRecordInterface $activeRecordClassName
     * @param bool $caseInsensitive
     *
     * @return string
     */
    public static function likeOperator($activeRecordClassName, $caseInsensitive = true)
    {
        if ($caseInsensitive === false) {
            return 'like';
        }
        return self::dbIsPostgres($activeRecordClassName) ? 'ilike' : 'like';
    }

    /**
     * @param string|ActiveRecordInterface $activeRecordClassName
     *
     * @return bool
     */
    public static function dbIsPostgres($activeRecordClassName)
    {
        return $activeRecordClassName::getDb()->getDriverName() === 'pgsql';
    }

    /**
     * @param string|ActiveRecordInterface $activeRecordClassName
     * @param string $attributeName
     * @param string $type
     *
     * @return string
     */
    public static function convertIfNeeded($activeRecordClassName, $attributeName, $type)
    {
        return self::dbIsPostgres($activeRecordClassName) ? new Expression("$attributeName::$type") : $attributeName;
    }
}
