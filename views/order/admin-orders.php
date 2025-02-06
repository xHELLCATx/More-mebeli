<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Управление заказами';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-admin container py-4">
    <div class="mb-4">
        <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left"></i> К админ-панели
        </a>
    </div>

    <div class="mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'options' => ['class' => 'table-responsive'],
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'headerOptions' => ['style' => 'width: 60px;'],
                    ],
                    [
                        'attribute' => 'id',
                        'label' => '№ заказа',
                        'headerOptions' => ['style' => 'width: 100px;'],
                    ],
                    [
                        'attribute' => 'user_id',
                        'label' => 'Покупатель',
                        'value' => function($model) {
                            return $model->user->username;
                        },
                    ],
                    [
                        'attribute' => 'phone',
                        'headerOptions' => ['style' => 'width: 150px;'],
                    ],
                    [
                        'attribute' => 'delivery_address',
                        'format' => 'ntext',
                        'contentOptions' => ['style' => 'max-width: 200px; white-space: normal;'],
                    ],
                    [
                        'attribute' => 'total_sum',
                        'value' => function($model) {
                            return Yii::$app->formatter->asCurrency($model->total_sum);
                        },
                        'headerOptions' => ['style' => 'width: 120px;'],
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function($model) {
                            $statusClasses = [
                                'new' => 'bg-primary',
                                'processing' => 'bg-warning',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger'
                            ];
                            $statusLabels = [
                                'new' => 'Новый',
                                'processing' => 'В обработке',
                                'completed' => 'Выполнен',
                                'cancelled' => 'Отменён'
                            ];
                            $class = $statusClasses[$model->status] ?? 'bg-secondary';
                            $label = $statusLabels[$model->status] ?? $model->status;
                            return "<span class='badge {$class}'>{$label}</span>";
                        },
                        'headerOptions' => ['style' => 'width: 120px;'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['datetime', 'php:d.m.Y H:i'],
                        'headerOptions' => ['style' => 'width: 160px;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                        'contentOptions' => ['class' => 'text-nowrap'],
                        'buttons' => [
                            'update' => function($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-edit"></i>',
                                    $url,
                                    [
                                        'class' => 'btn btn-sm btn-outline-success me-1',
                                        'title' => 'Редактировать',
                                        'data-bs-toggle' => 'tooltip'
                                    ]
                                );
                            },
                            'delete' => function($url, $model) {
                                return Html::a(
                                    '<i class="fas fa-trash"></i>',
                                    $url,
                                    [
                                        'class' => 'btn btn-sm btn-outline-danger',
                                        'title' => 'Удалить',
                                        'data-bs-toggle' => 'tooltip',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Вы уверены, что хотите удалить этот заказ?'
                                    ]
                                );
                            },
                        ],
                        'headerOptions' => ['style' => 'width: 120px;'],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>

<style>
.order-admin .card {
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border: none;
}

.order-admin .table th {
    background-color: #f8f9fa;
    white-space: nowrap;
}

.order-admin .badge {
    font-size: 0.875rem;
    padding: 0.5em 0.75em;
}

.order-admin .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.order-admin .table td {
    vertical-align: middle;
}

.order-admin .back-btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Адаптив для мобилок */
@media (max-width: 768px) {
    .order-admin .table {
        font-size: 0.875rem;
    }
    
    .order-admin .btn-sm {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}
</style>

<?php
$this->registerJs("
    // Инициализация тултипов Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle=\"tooltip\"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
");
?>
