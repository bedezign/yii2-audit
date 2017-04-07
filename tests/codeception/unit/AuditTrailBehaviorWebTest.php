<?php

namespace tests\codeception\unit;

use tests\app\models\Post;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;

/**
 * AuditTrailBehaviorWebTest
 */
class AuditTrailBehaviorWebTest extends AuditTestCase
{
    use Specify;

    /**
     * @var string
     */
    public $appConfig = '@tests/codeception/config/functional.php';

    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * Create Post
     */
    public function testCreatePost()
    {
        $this->entry();
        $this->finalizeAudit();

        $post = new Post();
        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());

        $entry = $this->entry();
        $this->finalizeAudit();

        $this->tester->seeRecord(Post::className(), [
            'id' => $post->id,
            'title' => 'New post title',
            'body' => 'New post body',
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => $entry->id,
            'request_method' => 'GET',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'id',
            'old_value' => '',
            'new_value' => $post->id,
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
            'old_value' => '',
            'new_value' => 'New post title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $entry->id,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
            'old_value' => '',
            'new_value' => 'New post body',
        ]);
    }

    /**
     * Update Post
     */
    public function testUpdatePost()
    {
        $this->entry();
        $this->finalizeAudit();

        $oldEntryId = $this->getLastPk(AuditEntry::className());

        $post = Post::findOne(1);
        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $this->entry();
        $this->finalizeAudit();

        $newEntryId = $this->getLastPk(AuditEntry::className());
        $this->assertNotEquals($oldEntryId, $newEntryId, 'I expected that a new entry was added');

        $this->tester->seeRecord(Post::className(), [
            'id' => $post->id,
            'title' => 'Updated post title',
            'body' => 'Updated post body',
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => $newEntryId,
            'request_method' => 'GET',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $newEntryId,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
            'old_value' => 'Post title 1',
            'new_value' => 'Updated post title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $newEntryId,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'body',
            'old_value' => 'Post body 1',
            'new_value' => 'Updated post body',
        ]);
    }

    /**
     * Delete Post
     */
    public function testDeletePost()
    {
        $this->entry();
        $this->finalizeAudit();

        $oldEntryId = $this->getLastPk(AuditEntry::className());
        $post = Post::findOne(1);
        $this->assertSame($post->delete(), 1);

        $this->entry();
        $this->finalizeAudit();

        $newEntryId = $this->getLastPk(AuditEntry::className());
        $this->assertNotEquals($oldEntryId, $newEntryId, 'I expected that a new entry was added');

        $this->tester->dontSeeRecord(Post::className(), [
            'id' => $post->id,
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => $newEntryId,
            'request_method' => 'GET',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => $newEntryId,
            'action' => 'DELETE',
            'model' => Post::className(),
            'model_id' => $post->id,
        ]);
    }

}