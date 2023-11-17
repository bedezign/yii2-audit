<?php

namespace bedezign\yii2\audit\helpers;

use yii\db\ActiveRecord;

class DbHelper
{
    /**
     * Returns like operator depending on db driver
     *
     * @param string|ActiveRecord $activeRecordClassName
     * @param bool $caseInsensitive
     *
     * @return string
     */
    public static function likeOperator($activeRecordClassName, $caseInsensitive = true)
    {
        if ($caseInsensitive === false) {
            return 'like';
        }
        return $activeRecordClassName::getDb()->getDriverName() === 'pgsql' ? 'ilike' : 'like';
    }
}
