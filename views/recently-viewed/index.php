<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\LinkPager;

$this->title = 'Недавно просмотренные товары';
$this->params['breadcrumbs'][] = $this->title;

$totalViewed = $dataProvider->getTotalCount();
?>

<div class="recently-viewed-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-user"></i> Назад в профиль', ['/site/profile'], ['class' => 'btn btn-primary me-2']) ?>
            <?php if ($totalViewed > 0): ?>
                <?= Html::beginForm(['/recently-viewed/clear-history'], 'post', ['class' => 'd-inline']) ?>
                    <?= Html::submitButton(
                        '<i class="fas fa-trash"></i> Очистить историю',
                        [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите очистить всю историю просмотров?',
                            ],
                        ]
                    ) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Статистика -->
    <div class="stats-container mb-4">
        <div class="row">
            <div class="col-md-12">
                <div class="stats-card" style="background: #0d6efd;">
                    <div class="stats-info">
                        <h3>Всего просмотрено товаров</h3>
                        <div class="stats-number"><?= $totalViewed ?></div>
                    </div>
                    <div class="stats-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Список товаров -->
    <div class="management-card">
        <h3><i class="fas fa-history"></i> История просмотров</h3>
        <?php if ($totalViewed > 0): ?>
            <div class="row">
                <?php foreach ($dataProvider->getModels() as $model): ?>
                    <div class="col-md-4 mb-4">
                        <?= $this->render('_item', ['model' => $model]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Пагинация -->
            <div class="d-flex justify-content-center mt-4">
                <?= LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'options' => ['class' => 'pagination'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                    'maxButtonCount' => 5,
                ]) ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <i class="fas fa-info-circle"></i> История просмотров пуста
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$css = <<<CSS
    .recently-viewed-dashboard {
        padding: 20px;
    }
    
    .stats-container {
        margin-bottom: 30px;
    }
    
    .stats-card {
        padding: 20px;
        border-radius: 10px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
    }
    
    .stats-info h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: normal;
        opacity: 0.9;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: bold;
        margin-top: 10px;
    }
    
    .stats-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 4rem;
        opacity: 0.2;
    }
    
    .management-card {
        background: white;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    
    .management-card h3 {
        color: #333;
        margin-bottom: 25px;
        font-size: 1.5rem;
    }
    
    .management-card h3 i {
        margin-right: 10px;
        color: #0d6efd;
    }
    
    /* Основные стили карточек */
    .catalog-card {
        height: 700px;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .catalog-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .catalog-card .card-img-top {
        height: 500px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    
    .catalog-card .card-body {
        padding: 1.25rem;
    }
    
    .catalog-card .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #2c3e50;
    }
    
    .catalog-card .card-text {
        color: #666;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    
    /* Стили для пагинатора */
    .pagination {
        margin: 20px 0;
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .pagination .page-link {
        color: #212529;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin: 0 2px;
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #212529;
    }
    
    /* Адаптивные отступы */
    @media (max-width: 768px) {
        .catalog-card {
            height: auto;
        }
        
        .catalog-card .card-img-top {
            height: 300px;
        }
        
        .stats-number {
            font-size: 2rem;
        }
        
        .stats-icon {
            font-size: 3rem;
        }
    }
CSS;
$this->registerCss($css);

$js = <<<JS
    $('.favorite-btn').click(function(e) {
        e.preventDefault();
        var btn = $(this);
        var productId = btn.data('product-id');
        
        $.ajax({
            url: '/Online_shop/web/site/toggle-favorite',
            type: 'POST',
            dataType: 'json',
            data: {
                id: productId,
                _csrf: yii.getCsrfToken()
            },
            success: function(response) {
                var icon = btn.find('i');
                if (response.status === 'added') {
                    icon.removeClass('far').addClass('fas');
                } else if (response.status === 'removed') {
                    icon.removeClass('fas').addClass('far');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('Ошибка:', textStatus, errorThrown);
            }
        });
    });
JS;
$this->registerJs($js);
?>
