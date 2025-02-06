<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы авторизации
 * Обеспечивает функционал входа пользователя в систему
 *
 * @property string $username Имя пользователя
 * @property string $password Пароль
 * @property boolean $rememberMe Запомнить меня
 * @property-read User|null $user Модель пользователя
 */
class LoginForm extends Model
{
    /**
     * @var string Имя пользователя
     */
    public $username;

    /**
     * @var string Пароль
     */
    public $password;

    /**
     * @var boolean Флаг "Запомнить меня"
     */
    public $rememberMe = false;

    /**
     * @var User|false|null Закэшированный экземпляр пользователя
     */
    private $_user = false;


    /**
     * Правила валидации полей формы
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['username', 'password'], 'required', 'message' => 'Поле не может быть пустым.'],
            // Проверка типа boolean для флага "Запомнить меня"
            ['rememberMe', 'boolean'],
            // Валидация пароля через метод validatePassword()
            ['password', 'validatePassword', 'message' => 'Неверное имя пользователя или пароль.'],
        ];
    }

    /**
     * Валидация пароля
     * Метод проверяет корректность введенного пароля
     *
     * @param string $attribute Проверяемый атрибут
     * @param array $params Дополнительные параметры правила валидации
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверное имя пользователя или пароль.');
            }
        }
    }

    /**
     * Выполняет вход пользователя в систему
     * Если установлен флаг "Запомнить меня", сессия сохраняется на 30 дней
     * 
     * @return boolean Результат авторизации
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Поиск пользователя по имени пользователя
     * Результат кэшируется в приватном свойстве $_user
     *
     * @return User|null Модель пользователя или null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
