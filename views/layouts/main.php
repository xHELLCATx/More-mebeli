<?php

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\models\Settings;

AppAsset::register($this);
$this->registerCssFile('@web/css/cart.css');
$this->registerCssFile('@web/css/navbar.css');
$this->registerCssFile('@web/css/form-control.css');
$this->registerCssFile('@web/css/admin.css');
$this->registerJsFile('@web/js/cart.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/favorites-counter.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="<?= Yii::getAlias('@web/images/icons/мм1-Photoroom.png') ?>">
    <?php 

    $controller = Yii::$app->controller->id;
    $action = Yii::$app->controller->action->id;
    $currentUrl = "/$controller/$action";
    
    // Для отладки
    if (Yii::$app->user->can('admin') || Yii::$app->user->can('owner')) {
        echo "<!-- SEO Debug Info:
Current URL: $currentUrl
";
        // Проверка на наличие SEO данных
        $pageSeo = \app\models\PageSeo::findOne(['page_url' => $currentUrl]);
        if ($pageSeo) {
            echo "Found SEO data:
- Title: {$pageSeo->meta_title}
- Description: {$pageSeo->meta_description}
- Keywords: {$pageSeo->meta_keywords}
";
        } else {
            echo "No SEO data found for this URL
";
            // Показываем все доступные URL
            $allSeo = \app\models\PageSeo::find()->select('page_url')->column();
            echo "Available URLs in database:
" . implode("\n", $allSeo) . "
";
        }
        echo "-->";
    }
    
    // Для страницы товара
    if ($controller === 'site' && $action === 'product' && isset($this->context->product)) {
        $product = $this->context->product;
        if ($product->meta_title) {
            echo Html::tag('title', Html::encode($product->meta_title));
        }
        if ($product->meta_description) {
            echo Html::tag('meta', '', ['name' => 'description', 'content' => Html::encode($product->meta_description)]);
        }
        if ($product->meta_keywords) {
            echo Html::tag('meta', '', ['name' => 'keywords', 'content' => Html::encode($product->meta_keywords)]);
        }
    } else {
        $pageSeo = \app\models\PageSeo::findOne(['page_url' => $currentUrl]);
        if ($pageSeo) {
            if ($pageSeo->meta_title) {
                echo Html::tag('title', Html::encode($pageSeo->meta_title));
            }
            if ($pageSeo->meta_description) {
                echo Html::tag('meta', '', ['name' => 'description', 'content' => Html::encode($pageSeo->meta_description)]);
            }
            if ($pageSeo->meta_keywords) {
                echo Html::tag('meta', '', ['name' => 'keywords', 'content' => Html::encode($pageSeo->meta_keywords)]);
            }
        } else {
            // Если SEO данных нет, используем стандартный заголовок
            echo Html::tag('title', Html::encode(Settings::getValue('site_name', $this->title)));
        }
    }
    ?>
    <?php $this->registerCsrfMetaTags() ?>
    <?php $this->head() ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .cart-icon-container {
            position: relative;
            display: inline-block;
        }
        
        #cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            font-size: 0.75rem;
            padding: 0.25em 0.5em;
            min-width: 1.5em;
            text-align: center;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<?php 
$this->registerJsFile(
    '@web/js/cart-counter.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => 'Море мебели',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
        'innerContainerOptions' => ['class' => 'container-fluid']
    ]);

    // Левое меню
    $leftMenuItems = [
        ['label' => 'Главная', 'url' => ['/site/index']],
        ['label' => 'Каталог', 'url' => ['/site/catalog']],
        ['label' => 'Статьи', 'url' => ['/article/index']],
        ['label' => 'О нас', 'url' => ['/site/about']],
        ['label' => 'Контакты', 'url' => ['/site/contact']],
    ];

    // Правое меню
    $rightMenuItems = [];
    
    if (Yii::$app->user->isGuest) {
        $rightMenuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    } else {
        $rightMenuItems[] = '<li class="nav-item dropdown">'
            . Html::beginTag('a', ['class' => 'nav-link dropdown-toggle', 'data-bs-toggle' => 'dropdown', 'href' => '#'])
            . Html::encode(Yii::$app->user->identity->username)
            . Html::endTag('a')
            . Html::beginTag('div', ['class' => 'dropdown-menu dropdown-menu-end'])
            . Html::a('Профиль', ['/site/profile'], ['class' => 'dropdown-item'])
            . (Yii::$app->user->identity->role === 'owner' || Yii::$app->user->identity->role === 'admin' ? Html::a('Админ панель', ['/admin'], ['class' => 'dropdown-item']) : '')
            . Html::a('Выйти', ['/site/logout'], [
                'class' => 'dropdown-item',
                'data' => ['method' => 'post']
            ])
            . Html::endTag('div')
            . '</li>';
        
        $cartCount = 0;
        $cart = Yii::$app->session->get('cart', []);
        foreach ($cart as $item) {
            $cartCount += $item['quantity'];
        }
        
        $rightMenuItems[] = [
            'label' => '<span class="cart-icon-container">
                          <i class="fas fa-shopping-cart"></i>
                          <span id="cart-count" class="badge rounded-pill bg-danger' . ($cartCount > 0 ? '' : ' d-none') . '">' . 
                          $cartCount . '</span>
                       </span>',
            'url' => ['/cart/index'],
            'encode' => false,
        ];
    }

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $leftMenuItems
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'],
        'items' => $rightMenuItems
    ]);

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-4 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h5>Контакты</h5>
                <p><i class="fas fa-phone"></i> 8 (800) 555-35-35</p>
                <p><i class="fas fa-map-marker-alt"></i> г. Краснодар, ул. Красная, 7а</p>
            </div>
            
            <div class="col-md-4">
                <h5>Мы в соцсетях</h5>
                <div class="social-links">
                    <a href="#" class="social-icon me-3">
                        <i class="fab fa-vk"></i>
                    </a>
                    <a href="#" class="social-icon">
                        <i class="fab fa-telegram-plane"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-md-4">
                <h5>Режим работы</h5>
                    Пн-Пт: 10:00 - 20:00<br>
                    Сб-Вс: 10:00 - 18:00<br>
                    Без перерыва<br>
                    Доставка по городу от 2 часов!
            </div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
