<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ViewAction;
use yii\rest\IndexAction;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\OptionsAction;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use app\models\News;

class NewsController extends ActiveController
{
    public $modelClass = News::class;

    public function actions()
    {
        return [
            'search' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => ViewAction::class,
                'modelClass' => $this->modelClass,
            ],
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
                'scenario' => $this->createScenario,
            ],
            'update' => [
                'class' => UpdateAction::class,
                'modelClass' => $this->modelClass,
                'scenario' => $this->updateScenario,
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'modelClass' => $this->modelClass,
            ],
            'options' => ['class' => OptionsAction::class],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
