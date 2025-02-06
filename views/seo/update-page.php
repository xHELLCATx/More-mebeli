<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование SEO: ' . $model->page_title;
?>

<div class="seo-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="seo-form">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'meta_description')->textarea(['rows' => 6]) ?>
        <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
