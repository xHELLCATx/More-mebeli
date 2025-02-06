<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Settings;

$this->title = 'Каталог товаров';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_product_item',
        'layout' => "{items}\n{pager}",
        'itemOptions' => ['class' => 'item'],
        'options' => ['class' => 'products-grid row'],
    ]) ?>

    <div class="catalog-description mt-5">
        <div class="card">
            <div class="card-body">
                <?= Settings::getValue('catalog_page_text', 'Наш каталог товаров') ?>
            </div>
        </div>
    </div>
</div>
