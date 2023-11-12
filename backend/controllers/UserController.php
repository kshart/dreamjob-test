<?php

namespace app\controllers;

use app\models\UserCollection;
use yii\rest\ViewAction;
use yii\rest\IndexAction;
use yii\rest\DeleteAction;
use yii\rest\OptionsAction;
use yii\rest\ActiveController;

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
