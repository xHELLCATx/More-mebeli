<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/components/TelegramBot.php';

use app\components\TelegramBot;

$bot = new TelegramBot();
$testData = [
    'name' => 'Test User',
    'email' => 'test@test.com',
    'subject' => 'Тестовое сообщение',
    'body' => 'Это тестовое сообщение для проверки работы бота'
];

$result = $bot->sendMessage($testData);
var_dump($result);
