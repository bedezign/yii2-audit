<?php

namespace tests\codeception\unit;

use tests\app\controllers\SiteController;
use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\tests\UnitTester;
use Yii;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditData;
use Codeception\Specify;
use yii\base\Action;
use yii\base\ActionEvent;

/**
 * AuditTest
 */
class AuditTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testThatEntryIsCreated()
    {
        $this->entry();
        $this->finalizeAudit(true);

        $oldEntryId = $this->getLastPk(AuditEntry::className());
        $this->entry();
        $this->finalizeAudit(true);

        $newEntryId = $this->getLastPk(AuditEntry::className());
        $this->entry();
        $this->finalizeAudit(true);

        $this->assertNotEquals($oldEntryId, $newEntryId, 'I expected that a new entry was added');
    }

    public function testThatCustomDataCanBeAttachedToAnEntry()
    {
        $this->entry();
        $this->finalizeAudit(true);

        $entryId = $this->getLastPk(AuditEntry::className());

        $originalData = ['some', 'array', 'with', 'test', 'data'];

        $oldDataId = $this->getLastPk(AuditData::className());
        $this->module()->data('extra-test', $originalData);

        $this->entry();
        $this->finalizeAudit(true);

        $newDataId = $this->getLastPk(AuditData::className());
        $this->assertNotEquals($oldDataId, $newDataId, 'I expected that a new data entry was added');

        $this->tester->seeRecord(AuditData::className(), [
            'entry_id' => $entryId,
            'type' => 'audit/extra',
        ]);

        //$data = AuditData::findOne($newDataId);
        //$this->assertEquals($)
    }

    public function testFindModuleIdentifier()
    {
        $this->assertEquals(Audit::getInstance()->findModuleIdentifier(), 'audit');
    }

    public function testFindModuleIdentifierWithoutModule()
    {
        $audit = Yii::$app->modules['audit'];
        Yii::$app->setModule('audit', null);
        $this->assertNull(Audit::getInstance()->findModuleIdentifier());
        Yii::$app->setModule('audit', $audit);
    }

    public function testOnBeforeAction()
    {
        // removed, can't create a controller since the unit is running as yii/web/Application see #147
        
        //$audit = Audit::getInstance();
        //$trackActions = $audit->trackActions;
        //$ignoreActions = $audit->ignoreActions;
        //$audit->trackActions = null;
        //$audit->ignoreActions = null;
        //
        //$controller = Yii::$app->createController('help/index');
        //$action = $controller[0]->createAction('index');
        //$event = new ActionEvent($action);
        //$audit->onBeforeAction($event);
        //
        //// just for coverage, not sure what to assert here...
        //$this->assertTrue(true);
        //
        //$audit->trackActions = $trackActions;
        //$audit->ignoreActions = $ignoreActions;
        //$audit->onBeforeAction($event);
    }

    /**
     * @dataProvider routeMatchingDataProvider
     */
    public function testThatRouteMatchingWorks($configuration, $action, $expectEntry)
    {
        $this->useEntry(null);
        $audit = $this->module();
        $audit->trackActions = $audit->ignoreActions = [];
        foreach ($configuration as $property => $value)
            $audit->$property = $value;

        $action = new Action($action[1], new SiteController($action[0], \Yii::$app));
        $event = new ActionEvent($action);
        $audit->onBeforeAction($event);

        if ($expectEntry)
            $this->assertNotNull($audit->getEntry(false));
        else
            $this->assertNull($audit->getEntry(false));
    }

    public function routeMatchingDataProvider()
    {
        return [
            [['trackActions' => ['site/track-action']], ['site', 'track-action'], true],
            [['trackActions' => ['site/track-action']], ['site', 'notrack-action'], false],
            [['ignoreActions' => ['site/notrack-action']], ['site', 'notrack-action'], false],
            [['ignoreActions' => ['site/notrack-action']], ['site', 'track-action'], true],

            [['trackActions' => ['site/*']], ['site', 'track-action'], true],
            [['trackActions' => ['site/*']], ['not-site', 'track-action'], false],
            [['ignoreActions' => ['site/*']], ['site', 'notrack-action'], false],
            [['ignoreActions' => ['site/*']], ['not-site', 'track-action'], true],

            [['trackActions' => ['*/track-action']], ['site', 'track-action'], true],
            [['trackActions' => ['*/track-action']], ['not-site', 'track-action'], true],
            [['trackActions' => ['*/track-action']], ['site', 'notrack-action'], false],
            [['ignoreActions' => ['*/notrack-action']], ['site', 'notrack-action'], false],
            [['ignoreActions' => ['*/notrack-action']], ['not-site', 'notrack-action'], false],
            [['ignoreActions' => ['*/notrack-action']], ['site', 'track-action'], true],
        ];
    }
}