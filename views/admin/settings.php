<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Settings;

$this->title = 'Настройки сайта';
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="admin-settings">
    <!-- Кнопка возврата -->
    <div class="mb-4">
        <a href="<?= Url::to(['/admin']) ?>" class="btn btn-outline-primary back-btn">
            <i class="fas fa-arrow-left"></i> К админ-панели
        </a>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Основные настройки</h5>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Тексты на страницах</h5>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Текст на главной странице</label>
                <?= Html::textarea('Settings[home_page_text]', 
                    Settings::getValue('home_page_text'), 
                    ['class' => 'form-control', 'rows' => 5]
                ) ?>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">SEO настройки</h5>
        </div>
        <div class="card-body">
            <p>Управление SEO данными для страниц сайта и товаров.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= Html::a('<i class="fas fa-cog"></i> Управление SEO страниц', ['admin/seo-pages'], [
                        'class' => 'btn btn-primary btn-lg w-100 mb-3'
                    ]) ?>
                    <div class="small text-muted">
                        Настройка мета-тегов для статических страниц сайта (главная, контакты, о нас и т.д.)
                    </div>
                </div>
                <div class="col-md-6">
                    <?= Html::a('<i class="fas fa-magic"></i> Добавить SEO по умолчанию', ['admin/init-default-seo'], [
                        'class' => 'btn btn-success btn-lg w-100 mb-3',
                        'data' => [
                            'confirm' => 'Это действие добавит SEO данные по умолчанию для основных страниц. Продолжить?',
                            'method' => 'post',
                        ],
                    ]) ?>
                    <div class="small text-muted">
                        Автоматическое добавление SEO данных для основных страниц сайта
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
