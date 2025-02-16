<?php
use yii\helpers\Html;
?>

<div class="article-card">
    <div class="article-image">
        <?php if ($model->image): ?>
            <img src="<?= Yii::getAlias('@web/' . $model->image) ?>" alt="<?= Html::encode($model->title) ?>">
        <?php else: ?>
            <img src="<?= Yii::getAlias('@web/images/no-image.jpg') ?>" alt="No image">
        <?php endif; ?>
    </div>
    <div class="article-content">
        <h3 class="article-title">
            <?= Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) ?>
        </h3>
        <div class="article-meta">
            <span class="text-muted">
                <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
            </span>
        </div>
    </div>
</div>
