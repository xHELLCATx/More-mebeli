<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы смены пароля
 * Обеспечивает валидацию и обработку смены пароля пользователя
 */
class ChangePasswordForm extends Model
{
    /**
     * @var string Текущий пароль пользователя
     */
    public $currentPassword;

    /**
     * @var string Новый пароль
     */
    public $newPassword;

    /**
     * @var string Подтверждение нового пароля
     */
    public $confirmPassword;

    /**
     * Правила валидации полей формы
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            [['currentPassword', 'newPassword', 'confirmPassword'], 'required'],
            ['currentPassword', 'validateCurrentPassword'],
            ['newPassword', 'string', 'min' => 6],
            ['confirmPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => 'Пароли не совпадают'],
        ];
    }

    /**
     * Названия полей формы для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'currentPassword' => 'Текущий пароль',
            'newPassword' => 'Новый пароль',
            'confirmPassword' => 'Подтверждение пароля',
        ];
    }

    /**
     * Проверяет правильность текущего пароля
     * @param string $attribute Проверяемый атрибут
     * @param array $params Дополнительные параметры
     */
    public function validateCurrentPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = Yii::$app->user->identity;
            if (!$user || !$user->validatePassword($this->currentPassword)) {
                $this->addError($attribute, 'Неверный текущий пароль');
            }
        }
    }

    /**
     * Выполняет смену пароля пользователя
     * Сохраняет новый пароль только после успешной валидации
     * @return boolean Результат смены пароля
     */
    public function changePassword()
    {
        if ($this->validate()) {
            $user = Yii::$app->user->identity;
            $user->setPassword($this->newPassword);
            return $user->save(false);
        }
        return false;
    }
}
