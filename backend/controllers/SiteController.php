<?php

namespace app\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\User;

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
                        'actions' => ['login', 'create-user'],
                        'roles' => ['?', '@'],
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

    /**
     * Залогиниться
     * Авторизация через токен в куках
     */
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

    /**
     * Выйти
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return 1;
    }

    /**
     * Текущий залогиненый пользователь
     */
    public function actionMe()
    {
        return Yii::$app->user->identity;
    }

    /**
     * Создать пользователя и залогинится под ним
     */
    public function actionCreateUser()
    {
        $model = new User();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $fields = Yii::$app->request->post();
            $model->load($fields, '');
            $model->generateAuthKey();
            $model->setPassword($fields['password']);
            if (!$model->save()) {
                $transaction->rollBack();
                $message = implode(',', $model->getErrorSummary(true));
                throw new BadRequestHttpException($message);
            }
            $transaction->commit();
            return $model;
        } catch (Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
