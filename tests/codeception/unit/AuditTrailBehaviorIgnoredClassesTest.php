<?php

namespace tests\codeception\unit;

use tests\app\models\Post;
use bedezign\yii2\audit\models\AuditTrail;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;

/**
 * AuditTrailBehaviorIgnoredClassesTest
 */
class AuditTrailBehaviorIgnoredClassesTest extends AuditTestCase
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
        $oldTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());
        $post = new Post();

        $post->getBehavior('audit')->ignoredClasses = [Post::className()];

        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());

        $newTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());
        $this->assertEquals($oldTrailId, $newTrailId, 'I expected that a new trail was not added');
    }

    /**
     * Update Post
     */
    public function testUpdatePost()
    {
        $oldTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());

        $post = Post::findOne(1);

        $post->getBehavior('audit')->ignoredClasses = [Post::className()];

        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $newTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());
        $this->assertEquals($oldTrailId, $newTrailId, 'I expected that a new trail was not added');
    }

    /**
     * Delete Post
     */
    public function testDeletePost()
    {
        $oldTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());

        $post = Post::findOne(1);

        $post->getBehavior('audit')->ignoredClasses = [Post::className()];

        $this->assertSame($post->delete(), 1);

        $newTrailId = $this->tester->fetchTheLastModelPk(AuditTrail::className());
        $this->assertEquals($oldTrailId, $newTrailId, 'I expected that a new trail was not added');
    }

}