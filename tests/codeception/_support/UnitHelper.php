<?php

namespace tests\codeception\_support;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class UnitHelper extends \Codeception\Module
{
    public function fetchTheLastModelPk($class)
    {
        return $class::find()->orderBy([reset($class::primaryKey()) => SORT_DESC])->scalar();
    }
}
