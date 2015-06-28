<?php

namespace tests\codeception\unit;

use bedezign\yii2\audit\Audit;
use bedezign\yii2\audit\tests\UnitTester;
use Yii;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditData;
use Codeception\Specify;
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
        $oldEntryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());
        $this->module()->getEntry(true);
        $newEntryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());
        $this->assertNotEquals($oldEntryId, $newEntryId, 'I expected that a new entry was added');
    }

    public function testThatCustomDataCanBeAttachedToAnEntry()
    {
        $this->module()->getEntry(true);
        $entryId = $this->tester->fetchTheLastModelPk(AuditEntry::className());

        $originalData = ['some', 'array', 'with', 'test', 'data'];

        $oldDataId = $this->tester->fetchTheLastModelPk(AuditData::className());
        $this->module()->data('extra-test', $originalData);
        $this->finalizeAudit();

        $newDataId = $this->tester->fetchTheLastModelPk(AuditData::className());
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
        $audit = Audit::getInstance();
        $trackActions = $audit->trackActions;
        $ignoreActions = $audit->ignoreActions;
        $audit->trackActions = null;
        $audit->ignoreActions = null;

        $action = Yii::$app->controller->createAction(null);
        $event = new ActionEvent($action);
        $audit->onBeforeAction($event);

        // just for coverage, not sure what to assert here...
        $this->assertTrue(true);

        $audit->trackActions = $trackActions;
        $audit->ignoreActions = $ignoreActions;
        $audit->onBeforeAction($event);
    }
}