<?php

use yii\helpers\Html;
use app\components\TextFormatter;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['/articles']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к статьям', ['/articles'], ['class' => 'btn btn-secondary']) ?>
        </div>
        <?php 
        $canEdit = !Yii::$app->user->isGuest && 
                  (in_array(Yii::$app->user->identity->role, ['admin', 'owner']) || 
                   $model->user_id === Yii::$app->user->id);
        ?>
        <?php if ($canEdit): ?>
        <div>
            <?= Html::a('Редактировать', ['article/update', 'seo_url' => $model->seo_url], ['class' => 'btn btn-primary me-2']) ?>
            <?= Html::a('Удалить', ['article/delete', 'seo_url' => $model->seo_url], [
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
                         alt="<?= Html::encode($model->img_alt) ?>" 
                         title="<?= Html::encode($model->img_title) ?>"
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
            <div class="article-content mt-4">
                <style>
                    .article-content p {
                        margin-bottom: 0;
                        margin-top: 1rem;
                    }
                </style>
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
    width: 350px;
    height: 120px;
}

.article-content {
    font-size: 16px;
    line-height: 1.6;
}

.article-content table {
    width: 100%;
    margin-bottom: 0;
    margin-top:1rem;
    border-collapse: collapse;
}

.article-content table td {
    padding: 0.75rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
}
CSS;

$this->registerCss($css);
?>
