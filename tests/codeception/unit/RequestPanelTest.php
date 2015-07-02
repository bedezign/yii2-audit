<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditEntrySearch;
use bedezign\yii2\audit\models\AuditErrorSearch;
use bedezign\yii2\audit\models\AuditJavascript;
use bedezign\yii2\audit\models\AuditJavascriptSearch;
use Codeception\Specify;
use yii\base\Action;
use yii\base\InlineAction;

class TestController extends \yii\base\Controller {}

/**
 * RequestPanelTest
 */
class RequestPanelTest extends AuditTestCase
{
    public function testInlineActionIsReturned()
    {
        \Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');

        $panel = $this->module()->getPanel('audit/request');
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getAction');
        $method->setAccessible(true);
        $this->assertEquals('tests\codeception\unit\TestController::test()', $method->invoke($panel));
    }

    public function testRegularActionIsReturned()
    {
        \Yii::$app->requestedAction = new Action('test', new TestController('testController', \Yii::$app));

        $panel = $this->module()->getPanel('audit/request');
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getAction');
        $method->setAccessible(true);
        $this->assertEquals('yii\base\Action::run()', $method->invoke($panel));
    }

    public function testActionRouteIsReturned()
    {
        \Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');

        $panel = $this->module()->getPanel('audit/request');
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getRoute');
        $method->setAccessible(true);
        $this->assertEquals('test-controller/test', $method->invoke($panel));
    }


    public function testRegularRouteIsReturned()
    {
        \Yii::$app->requestedAction = null;
        \Yii::$app->requestedRoute = 'other-controller/test-action';

        $panel = $this->module()->getPanel('audit/request');
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getRoute');
        $method->setAccessible(true);
        $this->assertEquals('other-controller/test-action', $method->invoke($panel));
    }

}