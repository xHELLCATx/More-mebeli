<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Оформление заказа';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/order/create.css');

$totalItems = 0;
$total = 0;
foreach ($cartItems as $item) {
    $totalItems += $item['quantity'];
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="order-dashboard">
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, #FF9966 0%, #FF5E62 100%);">
                    <div class="stats-info">
                        <h3>Товаров к заказу</h3>
                        <div class="stats-number"><?= $totalItems ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card" style="background: linear-gradient(135deg, #23BCBB 0%, #45E994 100%);">
                    <div class="stats-info">
                        <h3>Сумма заказа</h3>
                        <div class="stats-number"><?= Yii::$app->formatter->asCurrency($total) ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Форма заказа -->
        <div class="col-md-6">
            <div class="management-card">
                <h3>
                    <i class="fas fa-file-invoice"></i>
                    Данные для заказа
                </h3>

                <?php $form = ActiveForm::begin([
                    'id' => 'order-form',
                    'options' => ['class' => 'order-form'],
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control'],
                        'errorOptions' => ['class' => 'invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($model, 'phone')->textInput(['placeholder' => 'Введите ваш телефон']) ?>
                <?= $form->field($model, 'delivery_address')->textarea(['rows' => 3, 'placeholder' => 'Введите адрес доставки']) ?>
                <?= $form->field($model, 'comment')->textarea(['rows' => 3, 'placeholder' => 'Комментарий к заказу (необязательно)']) ?>
                <?= Html::activeHiddenInput($model, 'user_id', ['value' => Yii::$app->user->id]) ?>
                <?= Html::activeHiddenInput($model, 'total_sum', ['value' => $total]) ?>

                <div class="form-group mt-4">
                    <?= Html::submitButton('<i class="fas fa-check"></i> Подтвердить заказ', [
                        'class' => 'btn btn-primary btn-lg w-100'
                    ]) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <!-- Состав заказа -->
        <div class="col-md-6">
            <div class="management-card">
                <h3>
                    <i class="fas fa-shopping-basket"></i>
                    Состав заказа
                </h3>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Фото</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <?php if ($item['image']): ?>
                                            <img src="<?= Yii::getAlias('@web/uploads/') . $item['image'] ?>" 
                                                 alt="<?= Html::encode($item['name']) ?>"
                                                 style="max-width: 50px;">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= Html::encode($item['name']) ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($item['price']) ?></td>
                                    <td><?= $item['quantity'] ?></td>
                                    <td><?= Yii::$app->formatter->asCurrency($item['price'] * $item['quantity']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Итого:</strong></td>
                                <td><strong><?= Yii::$app->formatter->asCurrency($total) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    .order-dashboard {
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
        height: 100%;
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
    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #495057;
    }
    .invalid-feedback {
        font-size: 0.875rem;
    }
    .alert {
        border-radius: 8px;
    }
    .btn-primary {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
CSS;

$this->registerCss($css);
?>
