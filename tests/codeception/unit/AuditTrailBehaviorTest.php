<?php

namespace bedezign\yii2\audit\tests;

use app\models\Post;
use bedezign\yii2\audit\models\AuditEntry;
use bedezign\yii2\audit\models\AuditTrail;
use Codeception\Specify;
use yii\codeception\TestCase;

/**
 * AuditTrailBehaviorTest
 */
class AuditTrailBehaviorTest extends TestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * Create Post
     */
    public function testCreatePost()
    {
        $post = new Post();
        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());


        $this->tester->seeRecord(Post::className(), [
            'id' => $post->id,
            'title' => 'New post title',
            'body' => 'New post body',
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => 1,
            'request_method' => 'CLI',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'id',
            'old_value' => '',
            'new_value' => $post->id,
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
            'action' => 'CREATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
            'old_value' => '',
            'new_value' => 'New post title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
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
        $post = Post::findOne(1);
        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $this->tester->seeRecord(Post::className(), [
            'id' => $post->id,
            'title' => 'Updated post title',
            'body' => 'Updated post body',
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => 1,
            'request_method' => 'CLI',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
            'action' => 'UPDATE',
            'model' => Post::className(),
            'model_id' => $post->id,
            'field' => 'title',
            'old_value' => 'Post title 1',
            'new_value' => 'Updated post title',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
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
        $post = Post::findOne(1);
        $this->assertSame($post->delete(), 1);

        $this->tester->dontSeeRecord(Post::className(), [
            'id' => $post->id,
        ]);
        $this->tester->seeRecord(AuditEntry::className(), [
            'id' => 1,
            'request_method' => 'CLI',
        ]);
        $this->tester->seeRecord(AuditTrail::className(), [
            'entry_id' => 1,
            'action' => 'DELETE',
            'model' => Post::className(),
            'model_id' => $post->id,
        ]);
    }

}