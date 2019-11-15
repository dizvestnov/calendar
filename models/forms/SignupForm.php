<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class SignupForm extends Model
{
    /**
     * @var string Логин для создания пользователя
     */
    public $username;
    /**
     * @var string Новый пароль пользователя
     */
    public $password;

    /**
     * Названия атрибутов формы
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }

    /**
     * Правила валидации полей формы
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password'], 'string'],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            [['username'], 'string', 'min' => 3],
            [['password'], 'string', 'min' => 8, 'max' => 32],
        ];
    }

    /**
     * Попытка регистрации пользователя
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public function register()
    {
        // если валидация прошла успешно
        if ($this->validate()) {
            $user = new User([
                'username' => $this->username,
                'access_token' => "{$this->username}-token",
                //'created_at' => time(),
                //'updated_at' => time(),
            ]);

            $user->generateAuthKey();
            $user->password = $this->password;

            if ($user->save()) {
                // назначение пользователю базовой роли User
                $auth = Yii::$app->authManager;

                $role = $auth->getRole('user');

                $auth->assign($role, $user->id);

                return Yii::$app->user->login($user);
            }
        }

        // вернем false, если не прошла валидация
        return false;
    }
}
