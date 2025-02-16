<?php

require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

$orderData = [
    'customer_name' => 'Тестовый Клиент',
    'customer_phone' => '+7 (999) 123-45-67',
    'customer_email' => 'test@example.com',
    'delivery_address' => 'ул. Тестовая, д. 1, кв. 123',
    'comment' => 'Тестовый комментарий к заказу',
    'created_at' => time(),
    'status' => 'новый',
    'items' => [
        [
            'name' => 'Письменный стол "FANTOM"',
            'quantity' => 1,
            'price' => 19740.00
        ],
    ],
    'total_amount' => 19740.00
];

try {
    $result = Yii::$app->orderBot->sendOrderNotification($orderData);
    echo $result ? "Уведомление успешно отправлено!\n" : "Ошибка при отправке уведомления\n";
} catch (Exception $e) {
    echo "Произошла ошибка: " . $e->getMessage() . "\n";
}
