<?php
use yii\helpers\Html;
use app\models\Settings;

$this->title = 'Море мебели';
?>

<div class="site-index">
    <div class="p-4 mb-4 bg-light rounded-3">
        <div class="container">
            <div class="text-center">
                <?= Settings::getValue('home_page_text', 'Добро пожаловать в наш магазин!') ?>
            </div>
        </div>
    </div>
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Море мебели — ваш путь к уюту и комфорту</h1>
                
                <div class="welcome-text mb-5">
                    <p class="lead text-center">
                        Добро пожаловать в онлайн-магазин мебели в Краснодаре! Мы предлагаем широкий ассортимент качественной и стильной мебели, 
                        которая идеально впишется в ваш интерьер. От элегантных диванов и функциональных кухонных гарнитуров до уютных кроватей 
                        и практичных шкафов — у нас вы найдёте всё, чтобы создать пространство своей мечты.
                    </p>
                </div>

                <h2 class="text-center mb-4">Почему выбирают нас:</h2>
                <div class="row mb-5">
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="feature-card p-4 h-100 bg-light rounded shadow-sm">
                            <h3 class="h5 mb-3">Качество и надежность</h3>
                            <p class="mb-0">Каждая единица мебели создана с заботой о долговечности и вашем комфорте.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="feature-card p-4 h-100 bg-light rounded shadow-sm">
                            <h3 class="h5 mb-3">Современный дизайн</h3>
                            <p class="mb-0">Широкий выбор мебели, выполненной в различных стилях — от классики до минимализма.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="feature-card p-4 h-100 bg-light rounded shadow-sm">
                            <h3 class="h5 mb-3">Удобство покупок</h3>
                            <p class="mb-0">Подробные описания, качественные фото и возможность заказать всё, не выходя из дома.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="feature-card p-4 h-100 bg-light rounded shadow-sm">
                            <h3 class="h5 mb-3">Доставка и сборка</h3>
                            <p class="mb-0">Мы бережно доставим ваш заказ по Краснодару и поможем с установкой.</p>
                        </div>
                    </div>
                </div>

                <div class="text-center mb-5">
                    <p class="lead mb-4">
                        Создавайте уютный и функциональный интерьер с нами! "Море мебели" — где красота и практичность находят идеальное сочетание.
                    </p>
                    <p class="h4 mb-5">Посетите наш каталог уже сегодня и найдите мебель, которая вдохновляет!</p>
                    <?= Html::a('Перейти в каталог', ['/site/catalog'], ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-card {
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.welcome-text {
    max-width: 900px;
    margin: 0 auto;
}
</style>
