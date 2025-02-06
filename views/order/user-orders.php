<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

$this->title = 'Мои заказы';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/order/user-orders.css');

$totalOrders = $dataProvider->getTotalCount();
$totalSpent = 0;
foreach ($dataProvider->models as $order) {
    $totalSpent += $order->total_sum;
}
?>

<div class="orders-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-user"></i> Назад в профиль', ['/site/profile'], ['class' => 'btn btn-primary']) ?>
    </div>
    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Всего заказов</h3>
                        <div class="stats-number"><?= $totalOrders ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card" style="background: #198754;">
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

    <!-- Список заказов -->
    <div class="management-card">
        <h3>История заказов</h3>
        <div class="table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => '№ заказа',
                        'contentOptions' => ['style' => 'width: 100px;'],
                    ],
                    [
                        'attribute' => 'status',
                        'label' => 'Статус',
                        'contentOptions' => ['style' => 'width: 150px;'],
                        'value' => function($model) {
                            $statuses = [
                                'new' => 'Новый',
                                'processing' => 'В обработке',
                                'completed' => 'Выполнен',
                                'cancelled' => 'Отменён'
                            ];
                            $statusClasses = [
                                'new' => 'badge bg-primary',
                                'processing' => 'badge bg-warning',
                                'completed' => 'badge bg-success',
                                'cancelled' => 'badge bg-danger'
                            ];
                            $status = $statuses[$model->status] ?? $model->status;
                            $class = $statusClasses[$model->status] ?? 'badge bg-secondary';
                            return "<span class='$class'>$status</span>";
                        },
                        'format' => 'raw',
                    ],
                    [
                        'attribute' => 'total_sum',
                        'label' => 'Сумма заказа',
                        'contentOptions' => ['style' => 'width: 150px;'],
                        'format' => 'currency',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата создания',
                        'contentOptions' => ['style' => 'width: 200px;'],
                        'value' => function($model) {
                            return Helper::formatDate($model->created_at);
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'contentOptions' => ['style' => 'width: 100px; text-align: center;'],
                        'buttons' => [
                            'view' => function ($url, $model) {
                                return Html::a('<i class="fas fa-eye"></i> Просмотреть', ['order/view', 'id' => $model->id], [
                                    'class' => 'btn btn-primary btn-sm',
                                    'title' => 'Просмотреть заказ'
                                ]);
                            }
                        ]
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
    .orders-dashboard {
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
    }
    .stats-info h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: normal;
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
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .management-card h3 {
        margin-bottom: 20px;
        color: #333;
        font-size: 1.5rem;
    }
    .table th {
        background-color: #f8f9fa;
    }
    .badge {
        padding: 8px 12px;
        font-size: 0.9rem;
    }
CSS;

$this->registerCss($css);
?>
