<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$this->title = 'Каталог';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
    /* Основные стили карточек */
    .catalog-card {
        height: 700px;
        transition: transform 0.2s, box-shadow 0.2s, opacity 0.2s;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }
    
    .catalog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .catalog-card .card-img-top {
        height: 500px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    
    .catalog-card .card-body {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    
    .catalog-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #2c3e50;
    }
    
    .catalog-card .card-text {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
        flex: 1;
        overflow: hidden;
    }
    
    .catalog-card .price-buttons-container {
        margin-top: auto;
    }
    
    /* Стиль для товаров, которых нет в наличии */
    .catalog-card.out-of-stock {
        opacity: 0.7;
        background-color: rgba(0,0,0,0.15);
    }
    
    /* Стили для фильтров и поиска */
    .search-form .form-control,
    .filter-form .form-control,
    .filter-form .form-select {
        border-radius: 6px;
        border: 1px solid #ddd;
        padding: 0.5rem 1rem;
    }
    
    .search-form .form-control:focus,
    .filter-form .form-control:focus,
    .filter-form .form-select:focus {
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        border-color: #86b7fe;
    }
    
    .card {
        border-radius: 8px;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Стили для кнопок */
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-weight: 500;
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Стили для пагинтора */
    .pagination {
        margin: 20px 0;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .pagination .page-link {
        color: #212529;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin: 0 2px;
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #212529;
    }
    
    /* Адаптивные отступы */
    .catalog-page {
        padding: 20px 0;
    }
    
    @media (max-width: 768px) {
        .catalog-card {
            height: auto;
        }
        
        .catalog-card .card-img-top {
            height: 300px;
        }
    }
    
    .catalog-card .card-link {
        color: inherit;
        text-decoration: none;
    }
    
    .catalog-card .card-link:hover {
        text-decoration: none;
    }
    
    .catalog-card .buttons-container {
        position: relative;
        z-index: 2;
    }
CSS;
$this->registerCss($css);
?>

<div class="catalog-page">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row mb-4">
        <div class="col-md-12">
            <form method="get" class="d-flex mb-3 search-form">
                <input type="text" name="search" class="form-control me-2" placeholder="Поиск по названию" value="<?= Html::encode($searchQuery ?? '') ?>">
                <button type="submit" class="btn btn-primary">Поиск</button>
                <?php if (!empty($searchQuery)): ?>
                    <a href="<?= Url::to(['site/catalog']) ?>" class="btn btn-outline-secondary ms-2">Сбросить</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Фильтры -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Фильтры</h5>
                    <form id="filter-form" method="get" class="row g-3 filter-form">
                        <div class="col-md-3">
                            <label for="category" class="form-label">Категория</label>
                            <select id="category" name="category" class="form-select">
                                <option value="">Все категории</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= Html::encode($cat) ?>" 
                                            <?= $selectedCategory == $cat ? 'selected' : '' ?>>
                                        <?= Html::encode($cat) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="price_from" class="form-label">Цена от</label>
                            <input type="number" class="form-control" id="price_from" name="price_from"
                                   value="<?= Html::encode($priceFrom) ?>" placeholder="От">
                        </div>
                        <div class="col-md-3">
                            <label for="price_to" class="form-label">Цена до</label>
                            <input type="number" class="form-control" id="price_to" name="price_to"
                                   value="<?= Html::encode($priceTo) ?>" placeholder="До">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Применить</button>
                            <a href="<?= Url::to(['site/catalog']) ?>" class="btn btn-outline-secondary">Сбросить</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Товары -->
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 catalog-card <?= $product->stock_status === 'закончился' ? 'out-of-stock' : '' ?>" data-product-url="<?= Url::to(['site/product', 'seo_url' => $product->seo_url]) ?>">
                        <?php if ($product->image): ?>
                            <img src="<?= Yii::getAlias('@web/uploads/') . $product->image ?>" 
                                 class="card-img-top" 
                                 alt="<?= Html::encode($product->name) ?>"
                                 title="<?= Html::encode($product->name) ?>">
                        <?php endif; ?>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= Html::encode($product->name) ?></h5>
                            <p class="card-text"><?= Html::encode(mb_strlen($product->description) > 250 ? mb_substr($product->description, 0, 250) . '...' : $product->description) ?></p>
                        </div>

                        <div class="card-footer border-0 bg-transparent mt-auto">
                            <?php if ($product->hasValidDiscount()): ?>
                                <div class="mb-2">
                                    <span class="text-decoration-line-through text-muted">
                                        <?= Yii::$app->formatter->asCurrency($product->price) ?>
                                    </span>
                                    <span class="badge bg-danger ms-2">-<?= $product->discount_percent ?>%</span>
                                </div>
                                <div class="h5 mb-3 text-danger">
                                    <?= Yii::$app->formatter->asCurrency($product->getDiscountedPrice()) ?>
                                </div>
                            <?php else: ?>
                                <div class="h5 mb-3">
                                    <?= Yii::$app->formatter->asCurrency($product->price) ?>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex gap-2 buttons-container">
                                <?= Html::a('Подробнее', ['site/product', 'seo_url' => $product->seo_url], [
                                    'class' => 'btn btn-outline-primary flex-grow-1'
                                ]) ?>
                                <?php if (!Yii::$app->user->isGuest): ?>
                                    <?php if ($product->stock_status === 'закончился'): ?>
                                        <button class="btn btn-secondary" disabled>Товар закончился</button>
                                    <?php else: ?>
                                        <form class="add-to-cart-form" style="flex-grow: 1;">
                                            <input type="hidden" name="id" value="<?= $product->id ?>">
                                            <?= Html::button('В корзину', [
                                                'class' => 'btn btn-success w-100',
                                                'type' => 'submit'
                                            ]) ?>
                                        </form>
                                    <?php endif; ?>
                                    <button class="btn btn-outline-danger favorite-btn" data-product-id="<?= $product->id ?>">
                                        <i class="<?= $product->isFavorite(Yii::$app->user->id) ? 'fas' : 'far' ?> fa-heart"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Пагинтор -->
            <div class="col-12">
                <?= LinkPager::widget([
                    'pagination' => $pages,
                    'options' => ['class' => 'pagination justify-content-center'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
                ]) ?>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Товары не найдены. Попробуйте изменить параметры фильтра.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$js = <<<JS
    $(document).ready(function() {
        $('.catalog-card').on('click', function(e) {
            if (!$(e.target).closest('.buttons-container').length) {
                window.location.href = $(this).data('product-url');
            }
        });

        $('.add-to-cart-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var button = form.find('button[type="submit"]');
            var productId = form.find('input[name="id"]').val();
            
            button.prop('disabled', true);
            
            $.ajax({
                url: '/Online_shop/web/cart/add',
                type: 'POST',
                data: {
                    id: productId
                },
                success: function(response) {
                    if (response.success) {
                        $('#alert-container').html(response.html);
                        
                        var count = response.count;
                        var badge = $('#cart-count');
                        badge.text(count);
                        if (count > 0) {
                            badge.removeClass('d-none');
                        } else {
                            badge.addClass('d-none');
                        }
                    } else {
                        alert(response.message || 'Произошла ошибка');
                    }
                },
                error: function() {
                    alert('Произошла ошибка при добавлении товара в корзину');
                },
                complete: function() {
                    setTimeout(function() {
                        button.prop('disabled', false);
                    }, 1000);
                }
            });
        });
        
        $('.favorite-btn').click(function(e) {
            e.preventDefault();
            var btn = $(this);
            var heart = btn.find('i');
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
                    if (response.status === 'added') {
                        heart.removeClass('far').addClass('fas');
                    } else if (response.status === 'removed') {
                        heart.removeClass('fas').addClass('far');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Ошибка:', textStatus, errorThrown);
                }
            });
        });
    });
JS;
$this->registerJs($js);
?>
