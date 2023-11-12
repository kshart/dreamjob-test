<?php

namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Модель пользователя для авторизации.
 *
 * @property integer $id
 * @property string $phone Авторизация по телефону
 * @property string $name Имя пользователя
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $new_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function fields()
    {
        return [
            'id',
            'name',
            'phone',
            'created_at',
            'updated_at',
        ];
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
            [['phone'], 'default', 'value' => null],
            [['new_password'], 'string', 'min' => 5],
            [['new_password'], 'setPasswordHash'],
            [['auth_key'], 'string', 'max' => 32],
            [['name', 'auth_key'], 'required'],
            [['name', 'password_hash'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::find()
            ->where(['id' => $id])
            ->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        if (!$this->password_hash) {
            return false;
        }
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPasswordHash($attribute)
    {
        $this->setPassword($this->$attribute);
        $this->generateAuthKey();
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}
