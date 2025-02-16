<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Корзина';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/cart/cart.css');

$totalItems = count($cart);
$totalSum = 0;
foreach ($cart as $item) {
    $totalSum += $item['price'] * $item['quantity'];
}
?>

<div class="cart-dashboard">
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Товаров в корзине</h3>
                        <div class="stats-number"><?= $totalItems ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card" style="background: #198754;">
                    <div class="stats-info">
                        <h3>Общая сумма</h3>
                        <div class="stats-number"><?= Yii::$app->formatter->asCurrency($totalSum) ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Список товаров -->
    <div class="management-card">
        <h3><i class="fas fa-shopping-basket"></i> Товары в корзине</h3>
        
        <?php if (!empty($cart)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Сумма</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $id => $item): ?>
                            <tr>
                                <td>
                                    <?php if ($item['image']): ?>
                                        <img src="<?= Yii::getAlias('@web/uploads/') . $item['image'] ?>" 
                                             alt="<?= Html::encode($item['name']) ?>"
                                             title="<?= Html::encode($item['name']) ?>"
                                             style="max-width: 50px;">
                                    <?php endif; ?>
                                </td>
                                <td><?= Html::encode($item['name']) ?></td>
                                <td><?= Yii::$app->formatter->asCurrency($item['price']) ?></td>
                                <td>
                                    <div class="input-group" style="max-width: 150px;">
                                        <button class="btn btn-outline-secondary btn-quantity" 
                                                data-action="decrease" 
                                                data-product-id="<?= $id ?>">-</button>
                                        <input type="number" 
                                               class="form-control text-center quantity-input" 
                                               value="<?= $item['quantity'] ?>" 
                                               min="1" 
                                               data-product-id="<?= $id ?>">
                                        <button class="btn btn-outline-secondary btn-quantity" 
                                                data-action="increase" 
                                                data-product-id="<?= $id ?>">+</button>
                                    </div>
                                </td>
                                <td><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity']) ?></td>
                                <td>
                                    <?= Html::a('<i class="fas fa-trash"></i>', ['cart/remove', 'id' => $id], [
                                        'class' => 'btn btn-danger btn-sm',
                                        'data' => [
                                            'confirm' => 'Вы уверены, что хотите удалить этот товар из корзины?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <?= Html::a('Продолжить покупки', ['site/catalog'], ['class' => 'btn btn-outline-primary']) ?>
                    <?= Html::a('Очистить корзину', ['cart/clear'], [
                        'class' => 'btn btn-outline-danger ms-2',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите очистить корзину?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
                <?= Html::a('Оформить заказ', ['/order/create'], [
                    'class' => 'btn btn-success',
                    'data-method' => 'get'
                ]) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Ваша корзина пуста.
                <?= Html::a('Перейти к покупкам', ['site/catalog'], ['class' => 'alert-link']) ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$css = <<<CSS
    .cart-dashboard {
        padding: 20px;
    }
    .stats-container {
        margin-bottom: 30px;
    }
    .stats-card {
        border-radius: 10px;
        padding: 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    .stats-card:hover {
        transform: translateY(-5px);
    }
    .stats-info h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: normal;
        opacity: 0.9;
    }
    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        margin-top: 10px;
    }
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    .management-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .management-card h3 {
        margin-bottom: 25px;
        color: #333;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .management-card h3 i {
        color: #0d6efd;
    }
    .table th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-outline-secondary {
        border-color: #dee2e6;
    }
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
        color: #0d6efd;
    }
CSS;

$this->registerCss($css);

$js = <<<JS
    $('.btn-quantity').on('click', function() {
        var btn = $(this);
        var input = btn.closest('.input-group').find('.quantity-input');
        var currentValue = parseInt(input.val());
        var action = btn.data('action');
        
        if (action === 'decrease' && currentValue > 1) {
            input.val(currentValue - 1).trigger('change');
        } else if (action === 'increase') {
            input.val(currentValue + 1).trigger('change');
        }
    });

    $('.quantity-input').on('change', function() {
        var input = $(this);
        var productId = input.data('product-id');
        var quantity = parseInt(input.val());
        
        if (quantity < 1) {
            input.val(1);
            quantity = 1;
        }
        
        $.ajax({
            url: '/Online_shop/web/cart/update-quantity',
            type: 'POST',
            data: {
                id: productId,
                quantity: quantity
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Произошла ошибка');
                }
            },
            error: function() {
                alert('Произошла ошибка при обновлении количества');
            }
        });
    });
JS;
$this->registerJs($js);
?>
