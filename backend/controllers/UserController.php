<?php

namespace app\controllers;

use app\models\UserCollection;
use yii\web\ForbiddenHttpException;
use yii\rest\ViewAction;
use yii\rest\IndexAction;
use yii\rest\DeleteAction;
use yii\rest\OptionsAction;
use yii\rest\ActiveController;
use yii\filters\AccessControl;

class UserController extends ActiveController
{
    public $modelClass = UserCollection::class;

    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
            ],
            'options' => ['class' => OptionsAction::class],
        ];
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'delete', 'options'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => fn ($rule, $action) => throw new ForbiddenHttpException(),
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    // public function actionIndex()
    // {
    //     return 'ytоно';
    // }
}
