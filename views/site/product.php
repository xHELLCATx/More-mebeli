<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Каталог', 'url' => ['catalog']];
$this->params['breadcrumbs'][] = $this->title;

$images = $product->getAllImages();
$isFavorite = $product->isFavorite(Yii::$app->user->id);
?>

<div class="product-page">
    <?= Html::a('<i class="fas fa-arrow-left"></i> Назад в каталог', ['site/catalog'], ['class' => 'btn btn-outline-secondary mb-4']) ?>
    
    <div class="row">
        <div class="col-md-6">
            <!-- Слайд-шоу изображений -->
            <?php if (!empty($images)): ?>
                <div id="productCarousel" class="carousel slide mb-4" data-bs-interval="false">
                    <!-- Слайды -->
                    <div class="carousel-inner">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                <img src="<?= Yii::getAlias('@web/uploads/') . $image ?>" 
                                     class="d-block w-100 rounded product-image" 
                                     alt="<?= Html::encode($product->name) ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Кнопки навигации -->
                    <?php if (count($images) > 1): ?>
                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Предыдущий</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Следующий</span>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Миниатюры -->
                <?php if (count($images) > 1): ?>
                    <div class="row thumbnails">
                        <?php foreach ($images as $index => $image): ?>
                            <div class="col-3 mb-3">
                                <img src="<?= Yii::getAlias('@web/uploads/') . $image ?>" 
                                     class="img-thumbnail <?= $index === 0 ? 'active' : '' ?>"
                                     data-bs-target="#productCarousel" 
                                     data-bs-slide-to="<?= $index ?>"
                                     alt="Thumbnail <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <h1><?= Html::encode($product->name) ?></h1>
            <p class="text-muted">Категория: <?= Html::encode($product->category) ?></p>
            <div class="description mb-4">
                <?= Html::encode($product->description) ?>
            </div>
            
            <div class="price-block mb-4">
                <?php if ($product->hasValidDiscount()): ?>
                    <div class="mb-2">
                        <span class="text-decoration-line-through text-muted h4">
                            <?= Yii::$app->formatter->asCurrency($product->price) ?>
                        </span>
                        <span class="badge bg-danger ms-2">-<?= $product->discount_percent ?>%</span>
                    </div>
                    <div class="h2 text-danger mb-3">
                        <?= Yii::$app->formatter->asCurrency($product->getDiscountedPrice()) ?>
                    </div>
                <?php else: ?>
                    <div class="h2 mb-3">
                        <?= Yii::$app->formatter->asCurrency($product->price) ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!Yii::$app->user->isGuest): ?>
                <div class="d-flex gap-2 mb-4">
                    <?php if ($product->stock_status === 'закончился'): ?>
                        <button class="btn btn-secondary btn-lg" disabled>Товар закончился</button>
                    <?php else: ?>
                        <form class="add-to-cart-form">
                            <input type="hidden" name="id" value="<?= $product->id ?>">
                            <?= Html::button('В корзину', [
                                'class' => 'btn btn-success btn-lg',
                                'type' => 'submit'
                            ]) ?>
                        </form>
                    <?php endif; ?>
                    <button class="btn btn-outline-danger favorite-btn" data-product-id="<?= $product->id ?>">
                        <i class="<?= $product->isFavorite(Yii::$app->user->id) ? 'fas' : 'far' ?> fa-heart"></i>
                    </button>
                </div>
            <?php else: ?>
                <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>
                        Чтобы добавить товар в избранное или корзину, пожалуйста, 
                        <?= Html::a('авторизуйтесь', ['/site/login'], ['class' => 'alert-link']) ?>
                    </div>
                </div>
                <div class="d-flex gap-2 mb-4">
                    <?= Html::a('В корзину', ['/site/login'], [
                        'class' => 'btn btn-success btn-lg disabled',
                        'aria-disabled' => 'true',
                        'data' => ['bs-toggle' => 'tooltip'],
                        'title' => 'Требуется авторизация'
                    ]) ?>
                    <?= Html::a('<i class="fas fa-heart-broken"></i>', ['/site/login'], [
                        'class' => 'btn btn-outline-danger btn-lg disabled',
                        'aria-disabled' => 'true',
                        'data' => ['bs-toggle' => 'tooltip'],
                        'title' => 'Требуется авторизация'
                    ]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (Yii::$app->user->can('admin') || Yii::$app->user->can('owner')): ?>
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">SEO Данные (отладка)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th>Поле</th>
                        <th>Значение</th>
                    </tr>
                    <tr>
                        <td>Meta Title</td>
                        <td><?= Html::encode($product->meta_title) ?></td>
                    </tr>
                    <tr>
                        <td>Meta Description</td>
                        <td><?= Html::encode($product->meta_description) ?></td>
                    </tr>
                    <tr>
                        <td>Meta Keywords</td>
                        <td><?= Html::encode($product->meta_keywords) ?></td>
                    </tr>
                    <tr>
                        <td>SEO URL</td>
                        <td><?= Html::encode($product->seo_url) ?></td>
                    </tr>
                </table>
            </div>
            <?= Html::a('Редактировать SEO', ['admin/update-product', 'id' => $product->id], ['class' => 'btn btn-primary mt-2']) ?>
        </div>
    </div>
<?php endif; ?>

<?php
$css = <<<CSS
    .product-image {
        height: 400px;
        object-fit: contain;
        background-color: #f8f9fa;
    }
    
    .carousel {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
    }
    
    .thumbnails img {
        cursor: pointer;
        height: 80px;
        object-fit: cover;
    }
    
    .thumbnails img.active {
        border-color: #0d6efd;
    }
CSS;
$this->registerCss($css);

$js = <<<JS
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
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
                    }
                },
                complete: function() {
                    button.prop('disabled', false);
                }
            });
        });

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
                    var icon = btn.find('i');
                    if (response.status === 'added') {
                        icon.removeClass('far').addClass('fas');
                    } else if (response.status === 'removed') {
                        icon.removeClass('fas').addClass('far');
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