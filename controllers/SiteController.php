<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\News;
use app\models\Category;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    const DEFAULT_PAGE_SIZE = 5;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $categories = Category::find()
            ->where(['status' => News::STATUS_ACTIVE])
            ->orderBy('caption')
            ->all();

        $query = News::find()
            ->joinWith('category')
            ->where(['news.status' => News::STATUS_ACTIVE])
            ->Andwhere(['category.status' => Category::STATUS_ACTIVE]);
        $count = $query->count();

        $pagination = New Pagination( ['totalCount' => $count, 'defaultPageSize' => SiteController::DEFAULT_PAGE_SIZE] );

        $news = $query->offset($pagination->offset)
            ->orderBy('date DESC')
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', ['categories' => $categories, 'news' => $news, 'pagination' => $pagination]);
    }

    public function actionCategory($id)
    {
        $cur_category = Category::findOne($id);

        if ($cur_category === null) {
            throw new NotFoundHttpException;
        } elseif ($cur_category->status === Category::STATUS_INACTIVE) {
            return $this->redirect(['index']);
        }

        $categories = Category::find()
            ->where(['status' => Category::STATUS_ACTIVE])
            ->orderBy('caption')
            ->all();

        $query = $cur_category->getNews()
            ->where(['status' => News::STATUS_ACTIVE])
            ->orderBy('date DESC');

        $count = $query->count();

        $pagination = New Pagination( ['totalCount' => $count, 'defaultPageSize' => NewsController::DEFAULT_PAGE_SIZE] );

        $news = $query->offset($pagination->offset)
            ->orderBy('date')
            ->limit($pagination->limit)
            ->all();

        return $this->render('index', ['categories' => $categories, 'news' => $news, 'pagination' => $pagination, 'cur_category'=> $cur_category]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
