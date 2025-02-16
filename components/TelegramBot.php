<?php

namespace app\components;

use Yii;
use yii\base\Component;

class TelegramBot extends Component
{
    // Токен вашего бота
    private $token = '7594826014:AAF24asrgz-mVGOrqGfoZadCf1HuOjqGX6s'; // TODO: Замените на ваш токен

    // ID вашего аккаунта
    private $chatId = '1090527231'; // TODO: Замените на ваш Chat ID

    /**
     * Отправляет сообщение в Telegram
     * @param array $data Данные для отправки
     * @return bool
     */
    public function sendMessage($data)
    {
        $message = "📨 Новое сообщение с сайта\n\n";
        $message .= "👤 Имя: " . $data['name'] . "\n";
        $message .= "📧 Email: " . $data['email'] . "\n";
        $message .= "📝 Тема: " . $data['subject'] . "\n";
        $message .= "💬 Сообщение:\n" . $data['body'];

        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";
        
        $postData = [
            'chat_id' => $this->chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($error) {
            error_log("Telegram Bot Error: " . $error);
            return false;
        }

        if ($httpCode !== 200) {
            error_log("Telegram API Response: " . $response);
            return false;
        }

        return true;
    }
}
