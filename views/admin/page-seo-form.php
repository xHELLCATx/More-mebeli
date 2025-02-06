<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Управление SEO', 'url' => ['seo-pages']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-seo-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'page_url')->textInput(['maxlength' => true])
        ->hint('Например: /site/contact или /site/about') ?>

    <?= $form->field($model, 'page_title')->textInput(['maxlength' => true])
        ->hint('Название страницы') ?>

    <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true])
        ->hint('Title тег для SEO (до 60 символов)') ?>

    <?= $form->field($model, 'meta_description')->textarea(['rows' => 3])
        ->hint('Meta Description для SEO (до 160 символов)') ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true])
        ->hint('Meta Keywords для SEO (через запятую)') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$css = <<<CSS
    .page-seo-form {
        max-width: 800px;
        padding: 20px;
    }
CSS;
$this->registerCss($css);
?>
