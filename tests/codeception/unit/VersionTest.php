<?php

namespace tests\codeception\unit;

use tests\app\models\Post;
use bedezign\yii2\audit\components\Version;
use bedezign\yii2\audit\tests\UnitTester;
use Codeception\Specify;

/**
 * VersionTest
 */
class VersionTest extends AuditTestCase
{
    use Specify;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testVersions()
    {
        $this->entry();
        $this->finalizeAudit();

        $post = new Post;
        $post->title = 'updated post title';
        $post->body = 'updated post body';
        $post->save();
        $post_id = $post->id;
        $this->finalizeAudit();

        $this->entry();
        $this->finalizeAudit();

        $post = Post::findOne($post_id);
        $post->title = 'only change the post title';
        $post->save();
        $this->finalizeAudit();

        $this->entry();
        $this->finalizeAudit();

        $post = Post::findOne($post_id);
        $post->body = 'only change the post body';
        $post->save();

        $this->entry();
        $this->finalizeAudit();

        $versions = Version::versions($post->className(), $post->id);

        $this->assertEquals(array_values($versions), [
            [
                'id' => '2',
                'title' => 'updated post title',
                'body' => 'updated post body',
            ],
            [
                'title' => 'only change the post title',
            ],
            [
                'body' => 'only change the post body',
            ],
        ]);
    }

    public function testFind()
    {
        $this->entry();
        $this->finalizeAudit();

        $post = new Post;
        $post->title = 'updated post title';
        $post->body = 'updated post body';
        $post->save();
        $post_id = $post->id;

        $this->entry();
        $this->finalizeAudit();

        $post = Post::findOne($post_id);
        $post->title = 'only change the post title';
        $post->save();
        $this->finalizeAudit();

        $this->entry();
        $this->finalizeAudit();
        $post = Post::findOne($post_id);
        $post->body = 'only change the post body';
        $post->save();
        $this->finalizeAudit();

        $versions = Version::versions($post->className(), $post->id);

        $lastVersion = key(array_slice($versions, -1, 1, true));
        /** @var Post $post */
        $post = Version::find($post->className(), $post->id, $lastVersion);
        $this->assertEquals($post->title, 'only change the post title');
        $this->assertEquals($post->body, 'only change the post body');

        $lastVersion = Version::lastVersion($post->className(), $post->id);
        /** @var Post $post */
        $post = Version::find($post->className(), $post->id, $lastVersion);
        $this->assertEquals($post->title, 'only change the post title');
        $this->assertEquals($post->body, 'updated post body');

        $lastVersion = key(array_slice($versions, -3, 1, true));
        /** @var Post $post */
        $post = Version::find($post->className(), $post->id, $lastVersion);
        $this->assertEquals($post->title, 'updated post title');
        $this->assertEquals($post->body, 'updated post body');
    }

}