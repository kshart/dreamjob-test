<?php

namespace app\jobs;

use yii\base\Exception;
use yii\base\BaseObject;
use yii\queue\JobInterface;
use app\models\News;

/**
 * Опубликовать новость.
 */
class NewsPublishJob extends BaseObject implements JobInterface
{
    /**
     * id новости
     */
    public $newsId;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        $news = News::findOne($this->newsId);
        if (!$news) {
            throw new Exception("Новость {$this->newsId} не найдена");
        }
        $news->is_draft = true;
        $news->save();
    }
}
