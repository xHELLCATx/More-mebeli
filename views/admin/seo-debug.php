<?php
use yii\helpers\Html;

$this->title = 'SEO Debug';
?>

<div class="seo-debug">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-header">
            <h5>Текущая страница</h5>
        </div>
        <div class="card-body">
            <p>Controller: <?= Yii::$app->controller->id ?></p>
            <p>Action: <?= Yii::$app->controller->action->id ?></p>
            <p>URL: /<?= Yii::$app->controller->id ?>/<?= Yii::$app->controller->action->id ?></p>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5>Записи в таблице page_seo</h5>
        </div>
        <div class="card-body">
            <?php
            $pages = \app\models\PageSeo::find()->all();
            if ($pages): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>URL</th>
                            <th>Title</th>
                            <th>Meta Title</th>
                            <th>Meta Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                        <tr>
                            <td><?= $page->id ?></td>
                            <td><?= Html::encode($page->page_url) ?></td>
                            <td><?= Html::encode($page->page_title) ?></td>
                            <td><?= Html::encode($page->meta_title) ?></td>
                            <td><?= Html::encode($page->meta_description) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning">
                    Нет записей в таблице page_seo
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
