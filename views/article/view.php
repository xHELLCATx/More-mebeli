<?php

use yii\helpers\Html;
use app\components\TextFormatter;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к статьям', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
        <?php 
        $canEdit = !Yii::$app->user->isGuest && 
                  (in_array(Yii::$app->user->identity->role, ['admin', 'owner']) || 
                   $model->user_id === Yii::$app->user->id);
        ?>
        <?php if ($canEdit): ?>
        <div>
            <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary me-2']) ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить эту статью?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="row">
        <!-- Левая колонка -->
        <div class="col-md-4">
            <?php if ($model->image): ?>
                <div class="article-thumbnail mb-4">
                    <img src="<?= Yii::getAlias('@web/' . $model->image) ?>" 
                         alt="<?= Html::encode($model->title) ?>" 
                         class="img-fluid rounded">
                </div>
            <?php endif; ?>
            
            <div class="article-meta">
                <h5>Информация о статье:</h5>
                <p class="mb-2">
                    <strong>Автор:</strong> <?= $model->user ? Html::encode($model->user->username) : 'Неизвестен' ?>
                </p>
                <p class="mb-2">
                    <strong>Создано:</strong> <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
                </p>
            </div>
        </div>
        
        <!-- Правая колонка -->
        <div class="col-md-8">
            <h1 class="mb-4"><?= Html::encode($this->title) ?></h1>
            <div class="article-content">
                <?= TextFormatter::formatText($model->content) ?>
            </div>
        </div>
    </div>
</div>

<?php
$css = <<<CSS
.article-thumbnail img {
    width: 350px;
    height: 350px;
    object-fit: cover;
}

.article-meta {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
}

.article-content {
    font-size: 16px;
    line-height: 1.6;
}
CSS;

$this->registerCss($css);
?>
