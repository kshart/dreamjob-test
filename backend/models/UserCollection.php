<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Модель пользователя для списка пользователей.
 *
 * @property News[] $newsList
 */
class UserCollection extends User
{
    public function fields()
    {
        return [
            'id',
            'name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsList()
    {
        return $this->hasMany(News::class, ['author_id' => 'id']);
    }
}
