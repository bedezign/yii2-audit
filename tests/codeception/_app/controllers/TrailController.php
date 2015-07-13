<?php

namespace tests\app\controllers;

use tests\app\models\Post;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * TrailController
 * @package tests\app\controllers
 */
class TrailController extends Controller
{

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        $post = new Post;
        if ($post->load(Yii::$app->request->post()) && $post->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Post has been created.'));
            return $this->redirect(['update', 'id' => $post->id]);
        }
        return $this->render('create', ['post' => $post]);
    }

    public function actionUpdate($id)
    {
        $post = $this->findPost($id);
        if ($post->load(Yii::$app->request->post()) && $post->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Post has been updated.'));
            return $this->redirect(['update', 'id' => $post->id]);
        }
        return $this->render('update', ['post' => $post]);
    }

    public function actionDelete($id)
    {
        $this->findPost($id)->delete();
        Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Post has been deleted.'));
        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return Post the loaded model
     * @throws HttpException
     */
    protected function findPost($id)
    {
        if (($post = Post::findOne($id)) !== null) {
            return $post;
        }
        throw new HttpException(404, 'The requested page does not exist.');
    }

}