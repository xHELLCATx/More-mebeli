<?php

use yii\helpers\Html;

$this->title = 'Создание статьи';
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-create">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Назад к статьям', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <h1 class="text-center mb-4"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
