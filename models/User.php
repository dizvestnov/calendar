<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\models;

use app\components\CachedRecordBehavior;
use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Класс - Пользователь
 *
 * @package app\models
 *
 * @property int $id [int(11)]  Номер пользователя
 * @property string $username [varchar(255)]  Логин
 * @property string $password_hash [varchar(255)]  Хеш пароля
 * @property string $auth_key [varchar(255)]  Ключ аутентификации
 * @property string $access_token [varchar(255)]  Ключ мгновенного доступа
 * @property int $created_at [int(11)]  Дата создания записи
 * @property int $updated_at [int(11)]  Дата последнего редактирования
 *
 * @property-write string $password Чистый пароль
 * @mixin CachedRecordBehavior
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * Набор поведений
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,

            //[
            //    'class' => TimestampBehavior::class,
            //    'updatedAtAttribute' => 'last_change_at',
            //],

            [
                'class' => CachedRecordBehavior::class,
                'prefix' => 'user',
            ],
        ];
    }

    /**
     * Названия атрибутов модели
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'username' => 'Логин',
            'password_hash' => 'Пароль',
            'auth_key' => 'Ключ авторизации',
            'access_token' => 'Токен доступа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата последнего изменения',
        ];
    }

    /**
     * Поиск пользователя по ID
     *
     * @param int|string $id
     *
     * @return User|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id' => $id]);
    }

    /**
     * Поиск пользователя по токену мгновенного доступа
     *
     * @param mixed $token
     * @param null $type
     *
     * @return User|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    /**
     * Обращение к уникальному ID пользователя
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Обращение к ключу авторизации (для авто-логина через cookie)
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Валидация ключа авторизации
     *
     * @param string $authKey
     *
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    /**
     * Поиск пользователя по логину username
     *
     * @param $username
     *
     * @return User|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    /**
     * Валидация хешей паролей
     *
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Генерация случайной строки для записи в токен авторизации
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Магический сеттер для установки пароля с автоматической генерацией хеша
     *
     * @param $password
     *
     * @throws Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}