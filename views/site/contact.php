<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <div class="container">
        <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

        <div class="row mb-5">
            <div class="col-md-4">
                <div class="contact-info-card card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-map-marker-alt fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title text-center">Адрес</h3>
                        <p class="card-text text-center">
                            г. Краснодар,<br>
                            ул. Красная, 7А<br>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-clock fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title text-center">Режим работы</h3>
                        <p class="card-text text-center">
                            Пн-Пт: 10:00 - 20:00<br>
                            Сб-Вс: 10:00 - 18:00<br>
                            Без перерыва
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-info-card card shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="fas fa-phone-alt fa-3x text-primary"></i>
                        </div>
                        <h3 class="card-title text-center">Телефоны</h3>
                        <p class="card-text text-center">
                            8 (800) 555-35-35<br>
                            8 (861) 222-33-44<br>
                            <small class="text-muted">Звонок бесплатный</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Напишите нам</h3>
                        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
                            <div class="alert alert-success">
                                Спасибо за ваше сообщение! Мы свяжемся с вами в ближайшее время.
                            </div>
                        <?php else: ?>
                            <p class="card-text">
                                Если у вас есть вопросы о нашей мебели или вы хотите получить консультацию, 
                                заполните форму ниже. Наши специалисты свяжутся с вами в течение рабочего дня.
                            </p>

                            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                                <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'placeholder' => 'Иван Иванов'])->label('Ваше имя') ?>

                                <?= $form->field($model, 'email')->textInput(['placeholder' => 'example@email.com'])->label('Email') ?>

                                <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Тема сообщения'])->label('Тема') ?>

                                <?= $form->field($model, 'body')->textarea(['rows' => 6, 'placeholder' => 'Ваше сообщение'])->label('Сообщение') ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg w-100', 'name' => 'contact-button']) ?>
                                </div>

                            <?php ActiveForm::end(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Дополнительная информация</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <h4><i class="fas fa-truck text-primary"></i> Доставка</h4>
                                <p>Мы осуществляем доставку по Краснодару и Краснодарскому краю. Стоимость и сроки доставки уточняйте у менеджеров.</p>
                            </div>
                            <div class="col-md-6">
                                <h4><i class="fas fa-tools text-primary"></i> Сборка</h4>
                                <p>Профессиональная сборка мебели производится нашими специалистами. Все необходимые инструменты у нас есть.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCss("
    .site-contact {
        padding: 40px 0;
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }
    .contact-info-card:hover {
        transform: translateY(-5px);
    }
    .card-title {
        color: #0056b3;
        margin-bottom: 1.5rem;
    }
    .fas {
        margin-right: 10px;
    }
    .text-primary {
        color: #0056b3 !important;
    }
    .btn-primary {
        background-color: #0056b3;
        border-color: #0056b3;
    }
    .btn-primary:hover {
        background-color: #004494;
        border-color: #004494;
    }
");
?>
