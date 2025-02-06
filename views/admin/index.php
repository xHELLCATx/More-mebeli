<?php
use yii\helpers\Html;
use app\models\User;
use app\models\Product;
use app\models\Order;

$this->title = 'Админ-панель';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/admin/admin.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

$userCount = User::find()->count();
$productCount = Product::find()->count();
$orderCount = Order::find()->count();

$lastUsers = User::find()
    ->orderBy(['id' => SORT_DESC])
    ->limit(3)
    ->all();

$lastProducts = Product::find()
    ->orderBy(['id' => SORT_DESC])
    ->limit(2)
    ->all();
?>

<div class="admin-dashboard">
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Пользователей</h3>
                        <div class="stats-number"><?= $userCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card" style="background: #198754;">
                    <div class="stats-info">
                        <h3>Товаров</h3>
                        <div class="stats-number"><?= $productCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-card" style="background: #0dcaf0;">
                    <div class="stats-info">
                        <h3>Заказов</h3>
                        <div class="stats-number"><?= $orderCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Блок управления -->
    <div class="management-container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="management-card">
                    <div class="card-icon">
                        <i class="fas fa-box"></i>
                    </div>
                    <div class="card-content">
                        <h3>Управление товарами</h3>
                        <p>Добавление, редактирование и удаление товаров</p>
                        <?= Html::a('Перейти', ['products'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="management-card">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="card-content">
                        <h3>Управление пользователями</h3>
                        <p>Просмотр и управление пользователями</p>
                        <?= Html::a('Перейти', ['users'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="management-card">
                    <div class="card-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="card-content">
                        <h3>Управление заказами</h3>
                        <p>Просмотр и управление заказами пользователей</p>
                        <?= Html::a('Перейти', ['/order/admin-orders'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="management-card">
                    <div class="card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div class="card-content">
                        <h3>Настройки сайта</h3>
                        <p>Управление контентом и настройками сайта</p>
                        <?= Html::a('Перейти', ['settings'], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Последние данные -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Последние пользователи</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($lastUsers as $user): ?>
                            <li class="mb-2">
                                <i class="fas fa-user me-2"></i>
                                <?= Html::encode($user->username) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Последние добавленные товары</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($lastProducts as $product): ?>
                            <li class="mb-2">
                                <i class="fas fa-box me-2"></i>
                                <?= Html::encode($product->name) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>