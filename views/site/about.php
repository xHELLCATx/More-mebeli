<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'О нас';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <div class="container">
        <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>
        
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <p class="lead text-center">
                            В "Море мебели" мы гордимся тем, что создаем уютные и стильные интерьеры для наших клиентов, 
                            опираясь на многолетний опыт и надежные партнерские отношения с лучшими мебельными фабриками России.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Надежные партнеры — гарантия качества</h3>
                        <p class="card-text">
                            Наш магазин тесно сотрудничает с ведущими производителями мебели, такими как фабрика 
                            "Мебель Черноземья" и "Лером". Эти компании известны своим безупречным качеством, 
                            современными технологиями и использованием экологичных материалов. Благодаря этому мы можем 
                            предложить вам мебель, которая сочетает в себе надежность, комфорт и стильный дизайн.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title">Более 10 лет на рынке</h3>
                        <p class="card-text">
                            Наша история началась более десяти лет назад, когда мы поставили перед собой цель — 
                            сделать качественную мебель доступной для каждой семьи в Краснодаре. За это время мы 
                            заслужили доверие тысяч клиентов и стали надежным проводником в мире стильных и 
                            функциональных интерьеров.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h3 class="card-title">Почему выбирают нас?</h3>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-star text-primary"></i>
                                <strong>Эксклюзивные коллекции:</strong> Благодаря прямому сотрудничеству с фабриками 
                                мы предлагаем модели, которые невозможно найти в других магазинах.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-user-friends text-primary"></i>
                                <strong>Индивидуальный подход:</strong> Мы знаем, что каждый дом уникален, поэтому 
                                готовы помочь вам найти мебель, идеально подходящую под ваш вкус и потребности.
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-3">
                                <i class="fas fa-check-circle text-primary"></i>
                                <strong>Проверенное качество:</strong> Все изделия проходят строгий контроль на 
                                каждом этапе производства.
                            </li>
                            <li class="mb-3">
                                <i class="fas fa-hands-helping text-primary"></i>
                                <strong>Поддержка на всех этапах:</strong> Мы поможем вам с выбором, организуем 
                                доставку и профессиональную сборку мебели.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h3 class="card-title">Наша миссия</h3>
                <p class="card-text">
                    Мы стремимся сделать процесс обустройства вашего дома легким, приятным и вдохновляющим. 
                    Каждый наш клиент становится частью нашей большой семьи, и мы всегда готовы помочь вам 
                    воплотить самые смелые интерьерные идеи.
                </p>
            </div>
        </div>

        <div class="text-center mb-4">
            <h2 class="display-4">"Море мебели" — ваш надежный проводник в мире комфорта и стиля! 🌊</h2>
        </div>
    </div>
</div>

<?php
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$this->registerCss("
    .site-about {
        padding: 40px 0;
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        transition: transform 0.3s ease;
    }
    .card:hover {
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
    .lead {
        font-size: 1.25rem;
        font-weight: 300;
    }
");
?>
