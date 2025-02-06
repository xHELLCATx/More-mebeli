<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Управление SEO страниц';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="seo-pages-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <h5>Доступные страницы для SEO:</h5>
        <ul>
            <li>/site/index - Главная страница</li>
            <li>/site/contact - Страница контактов</li>
            <li>/site/about - О нас</li>
            <li>/site/catalog - Каталог</li>
        </ul>
    </div>

    <p>
        <?= Html::a('Добавить SEO для страницы', ['admin/create-page-seo'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Добавить SEO по умолчанию', ['admin/init-default-seo'], [
            'class' => 'btn btn-primary',
            'data' => [
                'confirm' => 'Это действие добавит SEO данные по умолчанию для основных страниц. Продолжить?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'page_url',
            'page_title',
            'meta_title',
            [
                'attribute' => 'meta_description',
                'format' => 'ntext',
                'contentOptions' => ['style' => 'max-width: 300px; white-space: normal;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', ['admin/update-page-seo', 'id' => $model->id], [
                            'title' => 'Редактировать',
                            'class' => 'btn btn-primary btn-sm',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', ['admin/delete-page-seo', 'id' => $model->id], [
                            'title' => 'Удалить',
                            'class' => 'btn btn-danger btn-sm ml-1',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить эту запись?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>

<?php
$css = <<<CSS
    .seo-pages-index {
        padding: 20px;
    }
    .grid-view td {
        white-space: normal;
    }
CSS;
$this->registerCss($css);
?>
