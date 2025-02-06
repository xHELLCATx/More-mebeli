<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

// Устанавливаем локаль для PHP
setlocale(LC_TIME, 'ru_RU.UTF-8', 'rus_RUS.UTF-8', 'ru_RU', 'ru');
date_default_timezone_set('Europe/Moscow');

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
