<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Модель формы обратной связи
 * Обеспечивает функционал отправки сообщений через контактную форму
 */
class ContactForm extends Model
{
    /**
     * @var string Имя отправителя
     */
    public $name;

    /**
     * @var string Email отправителя
     */
    public $email;

    /**
     * @var string Тема сообщения
     */
    public $subject;

    /**
     * @var string Текст сообщения
     */
    public $body;

    /**
     * @var string Код проверки (капча)
     */
    public $verifyCode;


    /**
     * Правила валидации полей формы
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['name', 'email', 'subject', 'body'], 'required'],
            // Проверка корректности email
            ['email', 'email'],
        ];
    }

    /**
     * Названия полей формы для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'email' => 'Email',
            'subject' => 'Тема',
            'body' => 'Сообщение',
            'verifyCode' => 'Проверочный код',
        ];
    }

    /**
     * Отправляет сообщение в Telegram
     * @return bool Результат отправки
     */
    public function contact()
    {
        if ($this->validate()) {
            // Отправляем сообщение в Telegram
            $telegram = new \app\components\TelegramBot();
            return $telegram->sendMessage([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'body' => $this->body
            ]);
        }
        return false;
    }
}
