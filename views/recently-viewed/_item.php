<?php
use yii\helpers\Html;
use yii\helpers\StringHelper;

$product = $model->product;
if (!$product) {
    return;
}
?>

<div class="card h-100 catalog-card">
    <?php if ($product->image): ?>
        <img src="<?= Yii::getAlias('@web/uploads/') . $product->image ?>" 
             class="card-img-top" 
             alt="<?= Html::encode($product->name) ?>">
    <?php endif; ?>
    
    <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= Html::encode($product->name) ?></h5>
        <p class="card-text flex-grow-1">
            <?= Html::encode(mb_strlen($product->description) > 250 ? mb_substr($product->description, 0, 250) . '...' : $product->description) ?>
        </p>
        
        <div class="mt-auto">
            <?php if ($product->hasValidDiscount()): ?>
                <div class="mb-2">
                    <span class="text-decoration-line-through text-muted">
                        <?= Yii::$app->formatter->asCurrency($product->price) ?>
                    </span>
                    <span class="badge bg-danger ms-2">-<?= $product->discount_percent ?>%</span>
                </div>
                <div class="h5 mb-3 text-danger">
                    <?= Yii::$app->formatter->asCurrency($product->getDiscountedPrice()) ?>
                </div>
            <?php else: ?>
                <div class="h5 mb-3">
                    <?= Yii::$app->formatter->asCurrency($product->price) ?>
                </div>
            <?php endif; ?>

            <div class="d-flex gap-2 mb-2">
                <?= Html::a('Подробнее', ['site/product', 'id' => $product->id], [
                    'class' => 'btn btn-outline-primary flex-grow-1'
                ]) ?>
                <?php if (!Yii::$app->user->isGuest): ?>
                    <button class="btn btn-outline-danger favorite-btn" data-product-id="<?= $product->id ?>">
                        <i class="<?= $product->isFavorite(Yii::$app->user->id) ? 'fas' : 'far' ?> fa-heart"></i>
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
