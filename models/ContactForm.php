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
            // Проверка капчи
            ['verifyCode', 'captcha'],
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
     * Отправляет email на указанный адрес используя данные из формы
     * @param string $email Целевой email адрес
     * @return boolean Результат отправки сообщения
     */
    public function contact($email)
    {
        if ($this->validate()) {
            Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->setSubject($this->subject)
                ->setTextBody($this->body)
                ->send();

            return true;
        }
        return false;
    }
}
