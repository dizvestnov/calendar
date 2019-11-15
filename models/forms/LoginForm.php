<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    /**
     * @var string Логин для поиска пользователя
     */
    public $username;
    /**
     * @var string Пароль для проверки совпадения
     */
    public $password;
    /**
     * @var bool Нужно ли сохранять сессию
     */
    public $rememberMe = true;

    /**
     * @var bool Временная переменная для работы с пользователем
     */
    private $_user = false;

    /**
     * Названия атрибутов формы
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * Правила валидации полей формы
     * @return array
     */
    public function rules()
    {
        return [
            // обязательно для заполнения
            [['username', 'password'], 'required'],

            // приведение к типу
            ['rememberMe', 'boolean'],

            // кастомная проверка через функцию
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Валидация хеша пароля
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        // если до этого не было ошибок, проверяем пароль
        if (!$this->hasErrors()) {
            // находим пользователя по логину
            $user = $this->getUser();

            // сверяем пароль по хешу, иначе добавим ошибку
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль');
            }
        }
    }

    /**
     * Попытка авторизации пользователя
     * @return bool
     */
    public function login()
    {
        // если валидация прошла успешно
        if ($this->validate()) {
            // если стоит "запомнить меня" - то авторизуем на месяц, иначе только на время работы браузера
            $duration = $this->rememberMe
                ? 3600 * 24 * 30
                : 0;

            // вернем результат авторизации пользователя
            return Yii::$app->user->login(
                $this->getUser(),
                $duration
            );
        }

        // вернем false, если не прошла валидация
        return false;
    }

    /**
     * Поиск пользователя по логину
     * @return User|bool|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
