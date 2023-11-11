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
 * @property boolean $is_draft
 * @property integer $author_id
 * @property integer $views
 * @property integer $created_at
 * @property integer $updated_at
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

    public function beforeSave($insert = true)
    {
        if ($insert) {
            $this->author_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
}
