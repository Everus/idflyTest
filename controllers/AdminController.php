<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 05.07.2015
 * Time: 14:03
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => null,
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}