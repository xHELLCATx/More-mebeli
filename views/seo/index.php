<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Управление SEO';
?>

<div class="seo-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить SEO данные по умолчанию', ['init-default'], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Вы уверены, что хотите добавить SEO данные по умолчанию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>URL страницы</th>
                    <th>Заголовок</th>
                    <th>Meta Title</th>
                    <th>Meta Description</th>
                    <th>Meta Keywords</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><?= Html::encode($page->page_url) ?></td>
                    <td><?= Html::encode($page->page_title) ?></td>
                    <td><?= Html::encode($page->meta_title) ?></td>
                    <td><?= Html::encode($page->meta_description) ?></td>
                    <td><?= Html::encode($page->meta_keywords) ?></td>
                    <td>
                        <?= Html::a('Редактировать', ['update-page', 'id' => $page->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
