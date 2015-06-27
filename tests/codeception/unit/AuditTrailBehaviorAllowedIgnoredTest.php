<?php

namespace tests\codeception\unit;

use app\models\Post;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;

/**
 * AuditTrailBehaviorAllowedIgnoredTest
 */
class AuditTrailBehaviorAllowedIgnoredTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testThatAllowedConfigurationWorksForCreate()
    {
        $entry = $this->entry(true);

        $post = new Post();
        $audit = $post->getBehavior('audit');
        $audit->allowed = ['body'];

        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());

        $this->finalizeAudit();

        $this->tester->dontSeeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
        ]);
    }

    public function testThatAllowedConfigurationWorksForUpdate()
    {
        $entry = $this->entry(true);
        $post = Post::findOne(1);
        $audit = $post->getBehavior('audit');
        $audit->allowed = ['title'];

        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $this->finalizeAudit();

        $this->tester->dontSeeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
        ]);
    }

    public function testThatIgnoredConfigurationWorksForCreate()
    {
        $entry = $this->entry(true);

        $post = new Post();
        $audit = $post->getBehavior('audit');
        $audit->ignored = ['title'];

        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());

        $this->finalizeAudit();

        $this->tester->dontSeeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
        ]);
    }

    public function testThatIgnoredConfigurationWorksForUpdate()
    {
        $entry = $this->entry(true);
        $post = Post::findOne(1);
        $audit = $post->getBehavior('audit');
        $audit->ignored = ['body'];

        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $this->finalizeAudit();

        $this->tester->dontSeeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
        ]);
    }
}