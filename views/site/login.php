<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
    .auth-card {
        background-color: #2d3436;
        border-radius: 15px;
        padding: 30px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        position: relative;
        margin: 60px auto 20px;
    }
    
    .alert {
        margin-bottom: 20px;
        border: none;
        border-radius: 8px;
    }
    
    .alert-success {
        background-color: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }
    
    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }
    
    .auth-avatar {
        width: 80px;
        height: 80px;
        background-color: #404749;
        border-radius: 50%;
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .auth-avatar i {
        font-size: 40px;
        color: #757575;
    }
    
    .auth-tabs {
        display: flex;
        margin-bottom: 30px;
        margin-top: 30px;
        border-bottom: 2px solid #404749;
    }
    
    .auth-tab {
        flex: 1;
        text-align: center;
        padding: 10px;
        color: #757575;
        text-decoration: none;
        font-weight: 500;
    }
    
    .auth-tab.active {
        color: #ffffff;
        border-bottom: 2px solid #ffffff;
        margin-bottom: -2px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-control {
        background-color: #404749 !important;
        border: none !important;
        color: #fff !important;
        padding: 12px !important;
        border-radius: 8px !important;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 2px rgba(255,255,255,0.5) !important;
    }
    
    .form-control::placeholder {
        color: #757575;
    }
    
    .btn-submit {
        background-color: #ffffff;
        color: #2d3436;
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        margin-top: 20px;
        transition: all 0.3s;
    }
    
    .btn-submit:hover {
        background-color: rgba(255,255,255,0.9);
        transform: translateY(-1px);
    }
    
    .form-label {
        color: #757575;
        margin-bottom: 8px;
    }
    
    .custom-control-label {
        color: #757575;
    }
    
    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #ffffff;
        border-color: #ffffff;
    }
CSS;
$this->registerCss($css);
?>

<div class="auth-card">
    <div class="auth-avatar">
        <i class="fas fa-user"></i>
    </div>
    
    <div class="auth-tabs">
        <a href="<?= Yii::$app->urlManager->createUrl(['site/login']) ?>" class="auth-tab active">Вход</a>
        <a href="<?= Yii::$app->urlManager->createUrl(['site/register']) ?>" class="auth-tab">Регистрация</a>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'form-label'],
            'inputOptions' => ['class' => 'form-control'],
            'errorOptions' => ['class' => 'invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Имя пользователя'])->label('Имя пользователя') ?>

    <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label('Пароль') ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n{error}",
        'class' => 'custom-control-input',
        'labelOptions' => ['class' => 'custom-control-label'],
    ])->label('Запомнить меня') ?>

    <?= Html::submitButton('Войти', ['class' => 'btn btn-submit']) ?>

    <?php ActiveForm::end(); ?>

</div>
