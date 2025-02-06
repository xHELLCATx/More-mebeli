<?php

namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Модель формы регистрации
 * Обеспечивает валидацию и обработку данных при регистрации нового пользователя
 */
class RegisterForm extends Model
{
    /**
     * @var string Имя пользователя
     */
    public $username;

    /**
     * @var string Email пользователя
     */
    public $email;

    /**
     * @var string Пароль
     */
    public $password;

    /**
     * Правила валидации полей формы
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['username', 'email', 'password'], 'required', 'message' => 'Поле не может быть пустым.'],
            // Проверка формата email
            ['email', 'email'],
            // Проверка уникальности имени пользователя
            ['username', 'unique', 'targetClass' => User::class, 'message' => 'Это имя пользователя уже занято.'],
            // Проверка уникальности email
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Этот email уже зарегистрирован.'],
            // Минимальная длина пароля
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Регистрация нового пользователя
     * Создает нового пользователя с указанными данными и сохраняет в базу
     * 
     * @return User|null Модель пользователя в случае успешной регистрации, null в случае ошибки
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->role = 'user'; 
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }
}
