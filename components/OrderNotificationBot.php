<?php

namespace app\components;

use Yii;
use yii\base\Component;

class OrderNotificationBot extends Component
{
    // Токен вашего бота
    private $token = '7780854782:AAEldFCxQKda7eUpQPq3g7UDrXfOVEh3GAI'; // TODO: Замените на токен нового бота

    // ID вашего аккаунта
    private $chatId = '1090527231'; // TODO: Замените на ваш Chat ID

    /**
     * Форматирует дату и время в русском формате
     * @param string|int $datetime Дата и время (строка или timestamp)
     * @return string Отформатированная дата и время
     */
    private function formatDateTime($datetime)
    {
        // Если передана строка даты, преобразуем её в timestamp
        if (!is_numeric($datetime)) {
            $datetime = strtotime($datetime);
        }

        $months = [
            1 => 'января', 2 => 'февраля', 3 => 'марта', 4 => 'апреля',
            5 => 'мая', 6 => 'июня', 7 => 'июля', 8 => 'августа',
            9 => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря'
        ];
        
        $day = date('j', $datetime);
        $month = $months[date('n', $datetime)];
        $year = date('Y', $datetime);
        $time = date('H:i', $datetime);
        
        return "{$day} {$month} {$year} в {$time}";
    }

    /**
     * Отправляет уведомление о новом заказе в Telegram
     * @param array $orderData Данные заказа
     * @return bool
     */
    public function sendOrderNotification($orderData)
    {
        $isNewOrder = !isset($orderData['is_update']);
        
        $message = $isNewOrder ? "🛍️ Новый заказ" : "📝 Обновление заказа";
        $message .= " №" . $orderData['order_id'] . "\n\n";
        
        // Информация о покупателе
        $message .= "👤 Заказчик: " . $orderData['customer_name'] . "\n";
        if (!empty($orderData['customer_phone'])) {
            $message .= "📱 Телефон: " . $orderData['customer_phone'] . "\n";
        }
        if (!empty($orderData['customer_email'])) {
            $message .= "📧 Email: " . $orderData['customer_email'] . "\n";
        }
        
        // Адрес доставки
        $message .= "\n📍 Адрес доставки:\n" . $orderData['delivery_address'] . "\n";
        
        // Комментарий к заказу
        if (!empty($orderData['comment'])) {
            $message .= "\n💭 Комментарий:\n" . $orderData['comment'] . "\n";
        }
        
        // Дата и время заказа
        if (!$isNewOrder) {
            $message .= "\n📅 Дата создания заказа: " . 
                       $this->formatDateTime($orderData['created_at']) . "\n";
            $message .= "📅 Дата обновления: " . 
                       $this->formatDateTime(time()) . "\n";
        } else {
            $message .= "\n📅 Дата заказа: " . 
                       $this->formatDateTime(time()) . "\n";
        }
        
        // Статус заказа с переводом на русский
        $russianStatus = $this->translateStatus($orderData['status']);
        $message .= "📊 Статус: " . $this->getStatusEmoji($orderData['status']) . " " . $russianStatus . "\n";

        // Список товаров
        $message .= "\n📦 Состав заказа:\n";
        foreach ($orderData['items'] as $item) {
            $message .= "• " . $item['name'] . " - " . $item['quantity'] . " шт. × " . 
                       Yii::$app->formatter->asCurrency($item['price']) . "\n";
        }

        // Итоговая сумма
        $message .= "\n💰 Итого: " . Yii::$app->formatter->asCurrency($orderData['total_amount']);

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
            error_log("Order Notification Bot Error: " . $error);
            return false;
        }

        if ($httpCode !== 200) {
            error_log("Telegram API Response: " . $response);
            return false;
        }

        return true;
    }

    /**
     * Возвращает эмодзи для статуса заказа
     * @param string $status Статус заказа
     * @return string
     */
    private function getStatusEmoji($status)
    {
        $statusEmojis = [
            'new' => '🆕',
            'processing' => '⚡',
            'confirmed' => '✅',
            'shipped' => '🚚',
            'delivered' => '📦',
            'completed' => '🎉',
            'cancelled' => '❌'
        ];

        return $statusEmojis[mb_strtolower($status)] ?? '❓';
    }

    /**
     * Переводит статус заказа на русский язык
     * @param string $status Статус заказа на английском
     * @return string Статус заказа на русском
     */
    private function translateStatus($status)
    {
        $translations = [
            'new' => 'новый',
            'processing' => 'в обработке',
            'confirmed' => 'подтвержден',
            'shipped' => 'отправлен',
            'delivered' => 'доставлен',
            'completed' => 'завершен',
            'cancelled' => 'отменен'
        ];

        return $translations[mb_strtolower($status)] ?? $status;
    }
}
