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
 * @property integer $id
 * @property string $phone Авторизация по телефону
 * @property string $name Имя пользователя
 * @property string $password_hash
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
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
}
