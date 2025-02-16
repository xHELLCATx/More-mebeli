<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Изменение статуса заказа #' . $order->id;
$this->params['breadcrumbs'][] = ['label' => 'Управление заказами', 'url' => ['orders']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="order-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="order-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($order, 'status')->dropDownList([
            'new' => 'Новый',
            'processing' => 'В обработке',
            'completed' => 'Завершен',
            'cancelled' => 'Отменен'
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['orders'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
