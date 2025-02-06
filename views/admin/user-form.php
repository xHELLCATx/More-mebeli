<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Создание пользователя' : 'Редактирование пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['users']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    
    <?php if (Yii::$app->user->identity->role === 'owner'): ?>
        <?= $form->field($model, 'role')->dropDownList([
            'user' => 'Пользователь',
            'admin' => 'Администратор',
            'owner' => 'Владелец'
        ], ['prompt' => 'Выберите роль']) ?>
    <?php endif; ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['users'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
