<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use app\models\LoginForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login'],
                        'roles' => ['?'],
                    ], [
                        'allow' => true,
                        'actions' => ['me', 'logout'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => fn ($rule, $action) => throw new ForbiddenHttpException(),
            ],
        ];
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return 1;
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post(), '') && $model->login()) {
            return 1;
        }
        throw new BadRequestHttpException();
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return 1;
    }

    public function actionMe()
    {
        return Yii::$app->user->identity;
    }
}
