<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\Helper;

$this->title = 'Управление заказами';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-orders">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID заказа</th>
                    <th>Пользователь</th>
                    <th>Статус</th>
                    <th>Сумма</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order->id ?></td>
                        <td><?= $order->user->username ?></td>
                        <td>
                            <span class="badge bg-<?= $order->status === 'completed' ? 'success' : 
                                ($order->status === 'processing' ? 'warning' : 
                                ($order->status === 'cancelled' ? 'danger' : 'secondary')) ?>">
                                <?= Yii::t('app', ucfirst($order->status)) ?>
                            </span>
                        </td>
                        <td><?= Yii::$app->formatter->asCurrency($order->total_sum) ?></td>
                        <td><?= Helper::formatDate($order->created_at) ?></td>
                        <td>
                            <?= Html::a('<i class="fas fa-edit"></i>', ['update-order-status', 'id' => $order->id], [
                                'class' => 'btn btn-primary btn-sm',
                                'title' => 'Изменить статус'
                            ]) ?>
                            <?= Html::a('<i class="fas fa-eye"></i>', ['order/view', 'id' => $order->id], [
                                'class' => 'btn btn-info btn-sm',
                                'title' => 'Просмотреть заказ'
                            ]) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
