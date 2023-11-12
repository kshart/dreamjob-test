<?php

namespace app\models;

use Yii;
use DateTime;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;
use app\models\User;

/**
 * Модель новостей.
 *
 * @property integer $id
 * @property string $slug
 * @property string $title
 * @property string $description
 * @property string[] $tags
 * @property boolean $is_draft Новость в черновиках
 * @property integer $author_id
 * @property integer $views
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $author
 */
class News extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug', 'title', 'description', 'is_draft'], 'required'],
            [['slug'], 'unique'],
            [['author_id'], 'integer'],
        ];
    }

    public function fields()
    {
        return [
            'id',
            'slug',
            'title',
            'description',
            'tags',
            'is_draft',
            'author',
            'author_id',
            'views',
            'created_at',
            'updated_at',
        ];
    }

    public function beforeSave($insert = true)
    {
        if ($insert) {
            $this->author_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }
}
