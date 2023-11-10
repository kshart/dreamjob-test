<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\rest\ActiveController;
use yii\filters\AccessControl;
use app\models\News;

class NewsController extends ActiveController
{
    public $modelClass = News::class;

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
