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

    public function testFormatAsQueryDoesntDoJson()
    {
        $this->assertNull(Helper::formatAsQuery('{"test":"data","test2":["data2","data3"]}'));
    }

    public function testFormatAsQueryWorks()
    {
        $this->assertEqualsIgnoreLineBreakType(<<<PHP
[
    'test' => 'var'
    'test2' => 'value1'
    'test3' => [
        0 => 'value3'
        1 => 'value2'
    ]
]
PHP
        , Helper::formatAsQuery('test=var&test2=value1&test3%5B0%5D=value3&test3%5B1%5D=value2'));
    }

    public function testFormatAsJsonOnlyAcceptsJson()
    {
        $this->assertNull(Helper::formatAsJSON('test=var&test2=value1&test3%5B0%5D=value3&test3%5B1%5D=value2'));
    }

    public function testFormatAsJsonWorks()
    {
        $this->assertEqualsIgnoreLineBreakType(<<<JSON
{
    "test": "data",
    "test2": [
        "data2",
        "data3"
    ]
}
JSON
        , Helper::formatAsJSON('{"test":"data","test2":["data2","data3"]}'));
    }

    public function testFormatAsXMLDoesntDoJson()
    {
        $this->assertNull(Helper::formatAsXML('{"test":"data","test2":["data2","data3"]}'));
    }

    public function testFormatAsXMLWorks()
    {
        $this->assertEqualsIgnoreLineBreakType(<<<XML
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;note&gt;
  &lt;to&gt;Tove&lt;/to&gt;
  &lt;from&gt;Jani&lt;/from&gt;
  &lt;heading&gt;Reminder&lt;/heading&gt;
  &lt;body&gt;Don't forget me this weekend!&lt;/body&gt;
&lt;/note&gt;

XML
        , Helper::formatAsXML('<?xml version="1.0" encoding="UTF-8"?><note><to>Tove</to><from>Jani</from><heading>Reminder</heading><body>Don\'t forget me this weekend!</body></note>'));
    }

    public function testFormatAsHTMLWorks()
    {
            $this->assertEqualsIgnoreLineBreakType(<<<HTML
&lt;!DOCTYPE html&gt;
&lt;html&gt;
&lt;head&gt;&lt;title&gt;Test&lt;/title&gt;&lt;/head&gt;
&lt;body&gt;HTML Test!&lt;/body&gt;
&lt;/html&gt;

HTML
            , Helper::formatAsHTML('<!DOCTYPE html><html><head><title>Test</title></head><body>HTML Test!</body></html>'));
    }

    public function testFormatAsHTMLDoesntDoJson()
    {
        $this->assertNull(Helper::formatAsHTML('{"test":"data","test2":["data2","data3"]}'));
    }

}