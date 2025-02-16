<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Создать статью', ['article/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <!-- Форма поиска -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?= Url::to(['/articles']) ?>" method="get" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Поиск по названию статьи..."
                                   value="<?= Html::encode($searchQuery) ?>">
                        </div>
                        <div class="col-md-2 d-flex">
                            <button type="submit" class="btn btn-primary me-2 flex-grow-1">Поиск</button>
                            <?php if ($searchQuery): ?>
                                <a href="<?= Url::to(['/articles']) ?>" class="btn btn-outline-secondary">Сбросить</a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if (empty($articles)): ?>
        <div class="alert alert-info">
            <?= $searchQuery ? 'По вашему запросу ничего не найдено' : 'Статей пока нет' ?>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($articles as $article): ?>
                <div class="col">
                    <?= $this->render('_article_item', ['model' => $article]) ?>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-center mt-4">
            <?= \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
                'options' => ['class' => 'pagination'],
                'linkContainerOptions' => ['class' => 'page-item'],
                'linkOptions' => ['class' => 'page-link'],
                'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'page-link'],
            ]) ?>
        </div>
    <?php endif; ?>
</div>

<?php
$css = <<<CSS
.card-body h2 {
    font-size: 1.5rem;
    color: #212529;
    margin-bottom: 1rem;
}

.card-body p {
    margin: 0 0 1rem 0;
    line-height: 1.5;
    color: #6c757d;
}

.article-meta {
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid rgba(0,0,0,.125);
    color: #666;
    font-size: 0.9rem;
}
CSS;
$this->registerCss($css);
?>
