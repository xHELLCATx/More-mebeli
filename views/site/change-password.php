<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Смена пароля';
$this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="change-password">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'currentPassword')->passwordInput() ?>

                    <?= $form->field($model, 'newPassword')->passwordInput() ?>

                    <?= $form->field($model, 'confirmPassword')->passwordInput() ?>

                    <div class="form-group text-center">
                        <?= Html::submitButton('Сменить пароль', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Отмена', ['profile'], ['class' => 'btn btn-secondary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
