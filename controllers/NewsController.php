<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\News;
use app\models\Category;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    const DEFAULT_PAGE_SIZE = 5;

    public function actionView($id)
    {
        $post = News::findOne($id);
        $categories = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->orderBy('caption')
            ->all();
        if ($post === null) {
            throw new NotFoundHttpException;
        } elseif ($post->status === News::STATUS_INACTIVE) {
            return $this->redirect(['site/index']);
        }

        return $this->render('view', ['post' => $post, 'categories' => $categories]);
    }
}