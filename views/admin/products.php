<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = 'Управление товарами';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/admin/admin.css');
$this->registerCssFile('@web/css/admin/table-actions.css');
?>

<div class="admin-products">
    <!-- Кнопка возврата -->
    <div class="mb-4">
        <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left"></i> К админ-панели
        </a>
    </div>
    <!-- Заголовок и кнопка добавления -->
    <div class="content-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1><?= Html::encode($this->title) ?></h1>
            <a href="<?= Url::to(['admin/create-product']) ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Добавить товар
            </a>
        </div>
    </div>

    <!-- Статистика товаров -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Всего товаров</h3>
                        <div class="stats-number"><?= $totalCount ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблица товаров -->
    <div class="content-card">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product->id ?></td>
                            <td><?= Html::encode($product->name) ?></td>
                            <td><?= number_format($product->price, 0, '.', ' ') ?> ₽</td>
                            <td class="action-column">
                                <div class="btn-group" role="group">
                                    <?= Html::a('<i class="fas fa-eye"></i>', ['/site/product', 'id' => $product->id], [
                                        'class' => 'btn btn-sm btn-success',
                                        'title' => 'Просмотр',
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-edit"></i>', ['update-product', 'id' => $product->id], [
                                        'class' => 'btn btn-sm btn-primary',
                                        'title' => 'Редактировать',
                                    ]) ?>
                                    <?= Html::a('<i class="fas fa-trash"></i>', ['delete-product', 'id' => $product->id], [
                                        'class' => 'btn btn-sm btn-danger',
                                        'title' => 'Удалить',
                                        'data' => [
                                            'confirm' => 'Вы уверены, что хотите удалить этот товар?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
