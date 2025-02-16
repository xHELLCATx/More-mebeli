<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\components\Helper;

$this->title = 'Заказ №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мои заказы', 'url' => ['user-orders']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/order/view.css');

$statuses = [
    'new' => ['label' => 'Новый', 'class' => 'bg-primary'],
    'processing' => ['label' => 'В обработке', 'class' => 'bg-warning'],
    'completed' => ['label' => 'Выполнен', 'class' => 'bg-success'],
    'cancelled' => ['label' => 'Отменён', 'class' => 'bg-danger']
];
$currentStatus = $statuses[$model->status] ?? ['label' => $model->status, 'class' => 'bg-secondary'];
?>

<div class="order-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-list"></i> К списку заказов', ['user-orders'], ['class' => 'btn btn-primary']) ?>
    </div>
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);">
                    <div class="stats-info">
                        <h3>Статус заказа</h3>
                        <div class="stats-number">
                            <span class="badge <?= $currentStatus['class'] ?>" style="font-size: 1rem;">
                                <?= $currentStatus['label'] ?>
                            </span>
                        </div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #FF9966 0%, #FF5E62 100%);">
                    <div class="stats-info">
                        <h3>Дата заказа</h3>
                        <div class="stats-number" style="font-size: 1.2rem;">
                            <?= Helper::formatDate($model->created_at) ?>
                        </div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card" style="background: linear-gradient(135deg, #23BCBB 0%, #45E994 100%);">
                    <div class="stats-info">
                        <h3>Сумма заказа</h3>
                        <div class="stats-number">
                            <?= Yii::$app->formatter->asCurrency($model->total_sum) ?>
                        </div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-ruble-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Информация о заказе -->
        <div class="col-md-6">
            <div class="management-card">
                <h3>
                    <i class="fas fa-info-circle"></i>
                    Информация о заказе
                </h3>
                
                <div class="info-list">
                    <div class="info-item">
                        <label>Телефон</label>
                        <div class="value"><?= Html::encode($model->phone) ?></div>
                    </div>
                    <div class="info-item">
                        <label>Адрес доставки</label>
                        <div class="value"><?= Html::encode($model->delivery_address) ?></div>
                    </div>
                    <?php if ($model->comment): ?>
                        <div class="info-item">
                            <label>Комментарий</label>
                            <div class="value"><?= Html::encode($model->comment) ?></div>
                        </div>
                    <?php endif; ?>
                </div>
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
                                <th>Товар</th>
                                <th style="width: 100px;">Кол-во</th>
                                <th style="width: 120px;">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($model->orderItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if ($item->product && $item->product->image): ?>
                                                <img src="<?= Yii::getAlias('@web/uploads/') . $item->product->image ?>" 
                                                     alt="<?= Html::encode($item->product->name) ?>"
                                                     title="<?= Html::encode($item->product->name) ?>"
                                                     class="me-3"
                                                     style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?= Html::encode($item->product ? $item->product->name : 'Товар удален') ?></h6>
                                                <small class="text-muted">Артикул: <?= $item->product_id ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?= $item->quantity ?> шт.
                                    </td>
                                    <td class="align-middle text-end">
                                        <?= Yii::$app->formatter->asCurrency($item->price * $item->quantity) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-end"><strong>Итого:</strong></td>
                                <td class="text-end"><strong><?= Yii::$app->formatter->asCurrency($model->total_sum) ?></strong></td>
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
    .info-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .info-item {
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .info-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .info-item label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: block;
    }
    .info-item .value {
        color: #333;
        font-size: 1.1rem;
    }
    .badge {
        padding: 0.5rem 1rem;
    }
CSS;

$this->registerCss($css);
?>
