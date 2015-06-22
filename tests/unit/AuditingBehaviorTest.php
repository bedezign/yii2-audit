<?php

namespace tests;

use PHPUnit_Extensions_Database_DataSet_QueryDataSet;
use tests\models\Post;
use Yii;

/**
 * AuditingBehaviorTest
 */
class AuditingBehaviorTest extends DatabaseTestCase
{

    /**
     * Create Post
     */
    public function testCreatePost()
    {
        $post = new Post();
        $post->title = 'New post title';
        $post->body = 'New post body';
        $this->assertTrue($post->save());

        //$dataSet = $this->getConnection()->createDataSet(['post', 'audit_entry', 'audit_trail']);
        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('post');
        $dataSet->addTable('audit_trail', 'SELECT id, action, model, model_id, field, old_value, new_value FROM audit_trail');
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-create-post.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    /**
     * Update Post
     */
    public function testUpdatePost()
    {
        $post = Post::findOne(2);
        $post->title = 'Updated post title';
        $post->body = 'Updated post body';
        $this->assertTrue($post->save());

        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('post');
        $dataSet->addTable('audit_trail', 'SELECT id, action, model, model_id, field, old_value, new_value FROM audit_trail');
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-update-post.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

    /**
     * Delete Post
     */
    public function testDeletePost()
    {
        $post = Post::findOne(3);
        $this->assertEquals($post->delete(), 1);

        $dataSet = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $dataSet->addTable('post');
        $dataSet->addTable('audit_trail', 'SELECT id, action, model, model_id FROM audit_trail');
        $expectedDataSet = $this->createFlatXMLDataSet(__DIR__ . '/data/test-delete-post.xml');
        $this->assertDataSetsEqual($expectedDataSet, $dataSet);
    }

}