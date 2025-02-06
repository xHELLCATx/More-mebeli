<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;

$this->title = 'Избранные товары';
$this->params['breadcrumbs'][] = $this->title;

$totalFavorites = count($favorites);
?>

<div class="favorites-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-user"></i> Назад в профиль', ['/site/profile'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="stats-card" style="background: #dc3545;">
                    <div class="stats-info">
                        <h3>Товаров в избранном</h3>
                        <div class="stats-number"><?= $totalFavorites ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Список товаров -->
    <div class="management-card">
        <h3><i class="fas fa-heart"></i> Избранные товары</h3>
        <?php if ($totalFavorites > 0): ?>
            <div class="row">
                <?php foreach ($favorites as $favorite): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 catalog-card">
                            <?php if ($favorite->product->image): ?>
                                <img src="<?= Yii::getAlias('@web/uploads/') . $favorite->product->image ?>" 
                                     class="card-img-top" 
                                     alt="<?= Html::encode($favorite->product->name) ?>">
                            <?php else: ?>
                                <img src="<?= Yii::getAlias('@web/images/no-image.png') ?>" 
                                     class="card-img-top" 
                                     alt="Нет изображения">
                            <?php endif; ?>
                            
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">
                                    <?= Html::encode($favorite->product->name) ?>
                                </h5>
                                <?php if ($favorite->product->description): ?>
                                    <p class="card-text flex-grow-1">
                                        <?= Html::encode(mb_strlen($favorite->product->description) > 250 ? mb_substr($favorite->product->description, 0, 250) . '...' : $favorite->product->description) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="mt-auto">
                                    <?php if ($favorite->product->hasValidDiscount()): ?>
                                        <div class="mb-2">
                                            <span class="text-decoration-line-through text-muted">
                                                <?= Yii::$app->formatter->asCurrency($favorite->product->price) ?>
                                            </span>
                                            <span class="badge bg-danger ms-2">-<?= $favorite->product->discount_percent ?>%</span>
                                        </div>
                                        <div class="h5 mb-3 text-danger">
                                            <?= Yii::$app->formatter->asCurrency($favorite->product->getDiscountedPrice()) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="h5 mb-3">
                                            <?= Yii::$app->formatter->asCurrency($favorite->product->price) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?= Html::a('Подробнее', ['site/product', 'id' => $favorite->product->id], [
                                            'class' => 'btn btn-outline-primary'
                                        ]) ?>
                                        <button class="btn btn-outline-danger favorite-btn" data-product-id="<?= $favorite->product->id ?>">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> У вас пока нет избранных товаров
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.favorites-dashboard {
    padding: 20px;
}

.stats-container {
    margin-bottom: 30px;
}

.stats-card {
    padding: 20px;
    border-radius: 10px;
    color: white;
    position: relative;
    overflow: hidden;
    min-height: 120px;
}

.stats-info {
    position: relative;
    z-index: 1;
}

.stats-info h3 {
    font-size: 1.2rem;
    margin-bottom: 10px;
    font-weight: normal;
}

.stats-number {
    font-size: 2.5rem;
    font-weight: bold;
}

.stats-icon {
    position: absolute;
    right: 20px;
    bottom: 20px;
    font-size: 4rem;
    opacity: 0.3;
}

.management-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.management-card h3 {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.5rem;
}

.catalog-card {
    transition: transform 0.2s;
    height: 100%;
}

.catalog-card:hover {
    transform: translateY(-5px);
}

.card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>

<?php
$js = <<<JS
    $('.favorite-btn').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        
        $.ajax({
            url: '/Online_shop/web/site/toggle-favorite',
            type: 'POST',
            dataType: 'json',
            data: {
                id: productId,
                _csrf: yii.getCsrfToken()
            },
            success: function(response) {
                if (response.status === 'removed') {
                    btn.closest('.col-md-4').fadeOut(function() {
                        $(this).remove();
                        if ($('.col-md-4').length === 0) {
                            location.reload();
                        }
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ошибка:', textStatus, errorThrown);
            }
        });
    });
JS;
$this->registerJs($js);
?>
