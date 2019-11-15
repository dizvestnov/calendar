<?php
/**
 * Created by Artyom Manchenkov
 * artyom@manchenkoff.me
 * manchenkoff.me © 2019
 */

namespace app\models\forms;

use app\models\User;
use yii\base\Model;

class UpdateUserForm extends Model
{
    public $username;
    public $password;
    public $repeatPassword;

    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'unique', 'targetClass' => User::class, 'targetAttribute' => 'username'],
            [['username', 'password', 'repeatPassword'], 'string'],
            [['password'], 'string', 'min' => 8, 'max' => 32],
            [['repeatPassword'], 'compare', 'compareAttribute' => 'password', 'skipOnEmpty' => false, 'when' => function (UpdateUserForm $form) {
                return !empty($form->password);
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Новый пароль',
            'repeatPassword' => 'Повторите пароль',
        ];
    }

    public function update(User $user)
    {
        if (!$this->validate()) {
            return false;
        }

        $user->username = $this->username;

        if (!empty($this->password)) {
            $user->password = $this->password;
        }

        return $user->save();
    }
}