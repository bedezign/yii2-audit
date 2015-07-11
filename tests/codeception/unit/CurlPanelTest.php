<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\panels\CurlPanel;
use Codeception\Specify;

require(__DIR__ . '/../_support/curlFaker.php');

/**
 * AuditTrailTest
 */
class CurlPanelTest extends AuditTestCase
{

    public function testCurlTrackRequestSetsOptions()
    {
        $curl = curl_init();

        $mock = $this->getMockBuilder('stdClass')->setMethods(['setOpt'])->getMock();
        $mock->expects($this->exactly(4))
            ->method('setOpt')
            ->withConsecutive(
                [$curl, CURLOPT_HEADERFUNCTION, $this->anything()],
                [$curl, CURLOPT_VERBOSE, true],
                [$curl, CURLOPT_STDERR, $this->anything()],
                [$curl, CURLOPT_RETURNTRANSFER, 1]
            );

        curl_callback('curl_setopt', [$mock, 'setOpt']);

        $this->assertTrue(Audit::getInstance()->curlBegin($curl, 'http://testing.com', ['post' => 'data']));
    }

    public function getCurlId($resource)
    {
        $reflection = new \ReflectionClass(CurlPanel::className());
        $id = $reflection->getMethod('id');
        $id->setAccessible(true);
        return $id->invoke(Audit::getInstance()->getPanel('audit/curl'), $resource);
    }

    public function testCurlFinalizeRequestCollectsInfo()
    {
        $curl = curl_init();

        $curlId = $this->getCurlId($curl);
        $panel = Audit::getInstance()->getPanel('audit/curl');

        // TrackRequest normally initializes this
        $panel->data = [$curlId => []];

        $mock = $this->getMockBuilder('stdClass')->setMethods(['getInfo', 'getContent', 'getErrorNo', 'getError'])->getMock();
        $mock->expects($this->once())->method('getInfo')
            ->willReturn([
                'url' => 'http://testing.com',
                'content_type' => 'text/json'
            ]);

        $mock->expects($this->once())->method('getContent')->willReturn('Testing content');
        $mock->expects($this->once())->method('getErrorNo')->willReturn(0);
        $mock->expects($this->once())->method('getError')->willReturn('');

        curl_callback('curl_getinfo', [$mock, 'getInfo']);
        curl_callback('curl_multi_getcontent', [$mock, 'getContent']);
        curl_callback('curl_errno', [$mock, 'getErrorNo']);
        curl_callback('curl_error', [$mock, 'getError']);

        $this->assertTrue(Audit::getInstance()->curlEnd($curl));

        $data = $panel->data[$curlId];
        $this->assertEquals([
           'content_type' => 'text/json',
           'effective_url' => 'http://testing.com',
           'content' => 'Testing content'
        ], $data);
    }

    public function testCurlFinalizeRequestCollectsErrorInfo()
    {
        $curl = curl_init();

        $curlId = $this->getCurlId($curl);
        $panel = Audit::getInstance()->getPanel('audit/curl');

        // TrackRequest normally initializes this
        $panel->data = [$curlId => []];

        $mock = $this->getMockBuilder('stdClass')->setMethods(
            ['getInfo', 'getContent', 'getErrorNo', 'getError']
        )->getMock();
        $mock->expects($this->once())->method('getInfo')
            ->willReturn([
                    'url' => 'http://testing.com',
                    'content_type' => 'text/json'
            ]);

        $mock->expects($this->once())->method('getContent')->willReturn('Testing content');
        $mock->expects($this->once())->method('getErrorNo')->willReturn(1234);
        $mock->expects($this->once())->method('getError')->willReturn('Testing error');

        curl_callback('curl_getinfo', [$mock, 'getInfo']);
        curl_callback('curl_multi_getcontent', [$mock, 'getContent']);
        curl_callback('curl_errno', [$mock, 'getErrorNo']);
        curl_callback('curl_error', [$mock, 'getError']);

        // Should return false because of error
        $this->assertFalse(Audit::getInstance()->curlEnd($curl));

        $data = $panel->data[$curlId];
        $this->assertEquals(
            [
                'content_type' => 'text/json',
                'effective_url' => 'http://testing.com',
                'content' => 'Testing content',
                'error' => 1234,
                'errorMessage' => 'Testing error',
            ],
            $data
        );
    }

    public function testDoRequestCallsAllInvolvedMethods()
    {
        $curlMock = $this->getMockBuilder('stdClass')->setMethods(['execute'])->getMock();
        $curlMock->expects($this->once())->method('execute')->willReturn('success');
        curl_callback('curl_exec', [$curlMock, 'execute']);

        $mock = $this->getMockBuilder(CurlPanel::className())->setMethods(['init', 'trackRequest', 'finalizeRequest'])->getMock();
        // Disable init(), registration of the utility functions fails since no module is configured for the mock
        $mock->method('init')->willReturn('true');
        $mock->expects($this->once())->method('trackRequest');
        $mock->expects($this->once())->method('finalizeRequest');

        $mock->module = Audit::getInstance();

        $this->assertEquals('success', $mock->doRequest(curl_init()));
    }
}