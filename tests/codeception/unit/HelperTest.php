<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\components\Helper;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;

/**
 * HelperTest
 */
class HelperTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testCompact()
    {
        $this->assertEquals(['value'], Helper::compact(['value']));
    }

    public function testCompactSimplifiesArrays()
    {
        $this->assertEquals(['value', 'value2'], Helper::compact([['value'], ['value2']], true));
    }

    public function testCompactRemovesVariablesThatAreTooBig()
    {
        $this->assertEquals(
            ['short' => 'ok', 'short2' => 'ok', '__removed' => ['toolong1', 'toolong2']],
            Helper::compact([
                'short' => 'ok',
                'toolong1' => 'Serialized, this would be too long!',
                'short2' => 'ok',
                'toolong2' => 'Serialized, this would also be too long!',
            ], false, 10));
    }

    public function testSerialize()
    {
        $this->module()->compressData = false;
        $data = ['test' => 'Test Data', ['more' => 'More test data']];
        $this->assertEquals(serialize($data), Helper::serialize($data, false));
    }

}