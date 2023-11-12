<?php

namespace app\controllers;

use Yii;
use yii\rest\ViewAction;
use yii\rest\CreateAction;
use yii\rest\DeleteAction;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\jobs\NewsPublishJob;
use app\models\News;

class NewsController extends ActiveController
{
    public $modelClass = News::class;

    public function actions()
    {
        return [
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
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Поиск по новостям
     * @param string $fts Полнотекстовый поиск
     * @param int $page Номер страницы, начиная с 1
     * @param int $limit Количество записей на странице
     */
    public function actionSearch(int $page = 1, int $limit = 10, ?string $fts = null)
    {
        $query = News::find()
            ->with('author')
            ->orderBy('created_at DESC');

        if (Yii::$app->user->isGuest) {
            $query->andWhere(['is_draft' => false]);
        } else {
            $query->andWhere([
                'OR',
                ['is_draft' => false],
                ['is_draft' => true, 'author_id' => Yii::$app->user->id]
            ]);
        }
        if ($fts) {
            $query->select(['news.*', 'fts.rank'])
                ->innerJoin(<<<SQL
                    (
                        SELECT id, ts_rank(news_make_tsvector(news.title, news.description, news.tags), tsquery) AS rank
                        FROM
                            news,
                            to_tsquery(regexp_replace(cast(websearch_to_tsquery(:fts) as TEXT), E'(\'\\\w+\')', E'\\\\1:*', 'g')) as tsquery
                        WHERE news_make_tsvector(news.title, news.description, news.tags) @@ tsquery
                    ) fts
                SQL, 'news.id = fts.id', ['fts' => $fts])
                ->orderBy('rank');
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $page - 1,
                'pageSize' => $limit,
            ],
        ]);
    }

    /**
     * Опубликовать новость.
     */
    public function actionPublish(int $id)
    {
        Yii::$app->queueNews->push(new NewsPublishJob([
            'newsId' => $id,
        ]));
    }
}
