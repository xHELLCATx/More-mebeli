<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * Модель пользователя
 * Реализует функционал аутентификации и управления пользователями
 *
 * @property int $id ID пользователя
 * @property string $username Имя пользователя
 * @property string $email Email пользователя
 * @property string $password_hash Хэш пароля
 * @property string $auth_key Ключ аутентификации
 * @property string $role Роль пользователя (user, admin, owner)
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @var string Временное хранение пароля (не сохраняется в БД)
     */
    public $password;

    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['username', 'email', 'role'], 'required'],
            // Ограничение длины строковых полей
            [['username', 'email'], 'string', 'max' => 255],
            // Проверка формата email
            [['email'], 'email'],
            // Проверка уникальности
            [['username', 'email'], 'unique'],
            // Проверка роли
            [['role'], 'string'],
            [['role'], 'in', 'range' => ['user', 'admin', 'owner']],
            // Ключ аутентификации
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * Названия полей для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'password_hash' => 'Хэш пароля',
            'auth_key' => 'Ключ аутентификации',
            'role' => 'Роль',
        ];
    }

    /**
     * Поиск пользователя по ID
     * @param int $id ID пользователя
     * @return User|null Найденный пользователь или null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Поиск пользователя по токену доступа
     * В данной реализации не используется
     * @return null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * Поиск пользователя по имени пользователя
     * @param string $username Имя пользователя
     * @return User|null Найденный пользователь или null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * Получает ID пользователя
     * @return int ID пользователя
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Получает ключ аутентификации
     * @return string Ключ аутентификации
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Проверяет ключ аутентификации
     * @param string $authKey Проверяемый ключ
     * @return boolean Результат проверки
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Генерирует новый ключ аутентификации
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Действия перед сохранением модели
     * Генерирует ключ аутентификации для новых пользователей
     * @param boolean $insert true для новой записи
     * @return boolean Результат выполнения
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * Устанавливает пароль пользователя
     * Генерирует хэш пароля для безопасного хранения
     * @param string $password Пароль в открытом виде
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Проверяет правильность пароля
     * @param string $password Проверяемый пароль
     * @return boolean Результат проверки
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Проверяет, является ли пользователь владельцем
     * @return boolean true если роль пользователя 'owner'
     */
    public function isOwner()
    {
        return $this->role === 'owner';
    }

    /**
     * Проверяет, является ли пользователь администратором
     * @return boolean true если роль пользователя 'admin'
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Проверяет, может ли пользователь управлять пользователями
     * @return boolean true если роль пользователя 'owner' или 'admin'
     */
    public function canManageUsers()
    {
        return $this->role === 'owner' || $this->role === 'admin';
    }

    /**
     * Проверяет, может ли пользователь управлять администраторами
     * @return boolean true если роль пользователя 'owner'
     */
    public function canManageAdmins()
    {
        return $this->role === 'owner';
    }
}
