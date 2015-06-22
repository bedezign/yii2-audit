<?php

namespace tests;

use PHPUnit_Extensions_Database_DataSet_QueryDataSet;
use tests\models\Post;
use Yii;

/**
 * TestTest
 */
class TestTest extends DatabaseTestCase
{

    public function testAttributeOrder()
    {
        $post = new Post();
        $this->assertEquals($post->attributes(), ['id', 'title', 'body']);
    }

}