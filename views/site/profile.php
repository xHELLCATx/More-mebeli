<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\StringHelper;
use yii\helpers\Url;

$this->title = 'Личный кабинет';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/site/profile.css');

$orderCount = \app\models\Order::find()->where(['user_id' => $user->id])->count();
$totalSpent = \app\models\Order::find()
    ->where(['user_id' => $user->id])
    ->sum('total_sum') ?? 0;
?>

<div class="profile-dashboard">
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, #FF9966 0%, #FF5E62 100%);">
                    <div class="stats-info">
                        <h3>Заказов сделано</h3>
                        <div class="stats-number"><?= $orderCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, #23BCBB 0%, #45E994 100%);">
                    <div class="stats-info">
                        <h3>Потрачено всего</h3>
                        <div class="stats-number"><?= Yii::$app->formatter->asCurrency($totalSpent) ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Массив с переводами ролей
    $roleTranslations = [
        'owner' => 'Владелец',
        'admin' => 'Администратор',
        'user' => 'Пользователь',
        // Добавьте другие роли по мере необходимости
    ];
    
    // Получение перевода роли
    $translatedRole = isset($roleTranslations[$role]) ? $roleTranslations[$role] : $role;
    ?>

    <!-- Информация профиля -->
    <div class="row">
        <div class="col-md-6">
            <div class="management-card">
                <h3>
                    <i class="fas fa-user-circle"></i>
                    Личные данные
                </h3>
                <div class="profile-info">
                    <div class="info-item">
                        <label>Имя пользователя</label>
                        <div class="value"><?= Html::encode($user->username) ?></div>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <div class="value"><?= Html::encode($user->email) ?></div>
                    </div>
                    <div class="info-item">
                        <label>Статус</label>
                        <div class="value">
                          <span class="badge bg-success"><?= Html::encode($translatedRole) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="management-card">
                <h3>
                    <i class="fas fa-cog"></i>
                    Управление
                </h3>
                <div class="action-buttons">
                    <?= Html::a('<i class="fas fa-shopping-cart"></i> Мои заказы', ['/order/user-orders', 'sort' => '-id'], [
                        'class' => 'btn btn-primary btn-lg btn-block mb-3'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-heart"></i> Избранное', ['site/favorites'], [
                        'class' => 'btn btn-outline-primary btn-lg btn-block mb-3'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-key"></i> Сменить пароль', ['change-password'], [
                        'class' => 'btn btn-outline-secondary btn-lg btn-block'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Недавно просмотренные товары -->
<div class="row mt-4">
    <div class="col-12">
        <div class="management-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>
                    <i class="fas fa-history"></i>
                    Недавно просмотренные
                </h3>
                <?= Html::a('Все просмотренные товары', ['/recently-viewed/index'], [
                    'class' => 'btn btn-outline-primary'
                ]) ?>
            </div>
            
            <div class="row">
                <?php
                $recentlyViewed = \app\models\RecentlyViewed::getRecentlyViewed(Yii::$app->user->id, 3);
                foreach ($recentlyViewed as $item): ?>
                    <?php if ($item->product): ?>
                        <div class="col-md-4">
                            <div class="card product-card">
                                <?php if ($item->product->image): ?>
                                    <img src="<?= Yii::getAlias('@web/uploads/') . $item->product->image ?>" 
                                         class="card-img-top" 
                                         alt="<?= Html::encode($item->product->name) ?>">
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?= Html::encode($item->product->name) ?></h5>
                                    <p class="card-text">
                                        <?= Html::encode(StringHelper::truncate($item->product->description, 100)) ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if ($item->product->hasValidDiscount()): ?>
                                            <div class="price-block">
                                                <span class="old-price text-decoration-line-through text-muted">
                                                    <?= Yii::$app->formatter->asCurrency($item->product->price) ?>
                                                </span>
                                                <span class="badge bg-danger ms-2">-<?= $item->product->discount_percent ?>%</span>
                                                <div class="new-price text-danger">
                                                    <?= Yii::$app->formatter->asCurrency($item->product->getDiscountedPrice()) ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <span class="price"><?= Yii::$app->formatter->asCurrency($item->product->price) ?></span>
                                        <?php endif; ?>
                                        <?= Html::a('Подробнее', ['site/product', 'id' => $item->product->id], ['class' => 'btn btn-primary']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    .profile-dashboard {
        padding: 20px;
    }

    .stats-card {
        padding: 20px;
        border-radius: 10px;
        color: white;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
    }

    .stats-info {
        position: relative;
        z-index: 1;
    }

    .stats-number {
        font-size: 2em;
        font-weight: bold;
        margin-top: 10px;
    }

    .stats-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 3em;
        opacity: 0.3;
    }

    .management-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .management-card h3 {
        margin-bottom: 20px;
        color: #333;
    }

    .management-card h3 i {
        margin-right: 10px;
        color: #0d6efd;
    }

    .info-item {
        margin-bottom: 15px;
    }

    .info-item label {
        color: #666;
        font-size: 0.9em;
        margin-bottom: 5px;
    }

    .info-item .value {
        font-size: 1.1em;
        color: #333;
    }

    .action-buttons .btn {
        width: 100%;
        text-align: left;
    }

    .action-buttons .btn i {
        margin-right: 10px;
    }

    /* Стили для карточек недавно просмотренных товаров */
    .product-card {
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .product-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    
    .product-card .card-body {
        padding: 15px;
    }
    
    .product-card .card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #2c3e50;
    }
    
    .product-card .card-text {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.4;
        margin-bottom: 1rem;
        height: 3.6em;
        overflow: hidden;
    }
    
    /* АдаптЫв */
    @media (max-width: 768px) {
        .product-card .card-img-top {
            height: 150px; 
        }
    }
CSS;

$this->registerCss($css);
?>
