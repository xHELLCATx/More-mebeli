<?php

use yii\helpers\Html;

$this->title = 'Редактирование статьи: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['/articles']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['article/view', 'seo_url' => $model->seo_url]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="article-update">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к статье', ['article/view', 'seo_url' => $model->seo_url], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
