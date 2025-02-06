<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование заказа №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Управление заказами', 'url' => ['admin-orders']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-update">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к списку', ['admin-orders'], ['class' => 'btn btn-outline-primary']) ?>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3">Информация о заказе</h4>
                    
                    <div class="mb-3">
                        <label class="form-label">Пользователь</label>
                        <div class="form-control-plaintext">
                            <?= Html::encode($model->user->username) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Дата создания</label>
                        <div class="form-control-plaintext">
                            <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d.m.Y H:i') ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <div class="form-control-plaintext">
                            <?= Html::encode($model->phone) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Адрес доставки</label>
                        <div class="form-control-plaintext">
                            <?= Html::encode($model->delivery_address) ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Комментарий к заказу</label>
                        <div class="form-control-plaintext">
                            <?= Html::encode($model->comment) ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'status')->dropDownList([
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'completed' => 'Выполнен',
                        'cancelled' => 'Отменён'
                    ]) ?>

                    <div class="mb-3">
                        <label class="form-label">Общая сумма</label>
                        <div class="form-control-plaintext">
                            <?= Yii::$app->formatter->asCurrency($model->total_sum) ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h4 class="mb-3">Товары в заказе</h4>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($model->orderItems as $item): ?>
                                    <tr>
                                        <td><?= Html::encode($item->product->name) ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($item->price) ?></td>
                                        <td><?= $item->quantity ?></td>
                                        <td><?= Yii::$app->formatter->asCurrency($item->price * $item->quantity) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group mt-4">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<style>
.order-update .card {
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border: none;
}

.order-update .table th {
    background-color: #f8f9fa;
}
</style>
