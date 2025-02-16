<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => 'Админ-панель', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Управление товарами', 'url' => ['products']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'id' => 'product-name']) ?>

    <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'id' => 'product-description']) ?>

    <?= $form->field($model, 'price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'stock_status')->dropDownList([
        'много' => 'Много',
        'мало' => 'Мало',
        'закончился' => 'Закончился'
    ]) ?>

    <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*']) ?>
    <?php if ($model->image): ?>
        <div class="mb-3">
            <label class="form-label">Текущее изображение:</label><br>
            <img src="<?= Yii::getAlias('@web/uploads/') . $model->image ?>" 
                 alt="<?= Html::encode($model->name) ?>"
                 title="<?= Html::encode($model->name) ?>"
                 class="img-thumbnail" style="max-width: 200px">
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'additionalImages[]')->fileInput([
        'multiple' => true,
        'accept' => 'image/*',
        'class' => 'form-control'
    ])->label('Дополнительные изображения (максимум 3)') ?>

    <?php if (!empty($model->productImages)): ?>
        <div class="mb-3">
            <label class="form-label">Текущие дополнительные изображения:</label>
            <div class="row">
                <?php foreach ($model->productImages as $image): ?>
                    <div class="col-md-4 mb-3">
                        <img src="<?= Yii::getAlias('@web/uploads/') . $image->image ?>" 
                             alt="<?= Html::encode($model->name) ?>"
                             title="<?= Html::encode($model->name) ?>"
                             class="img-thumbnail" style="max-width: 200px">
                        <?= Html::a('Удалить', ['delete-image', 'id' => $image->id], [
                            'class' => 'btn btn-danger btn-sm mt-2',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить это изображение?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Блок скидок -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Настройки скидки</h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'discount_percent')->textInput([
                'type' => 'number',
                'min' => '0',
                'max' => '100',
                'step' => '1'
            ]) ?>

            <?= $form->field($model, 'discount_start')->textInput([
                'type' => 'datetime-local',
                'class' => 'form-control'
            ]) ?>

            <?= $form->field($model, 'discount_end')->textInput([
                'type' => 'datetime-local',
                'class' => 'form-control'
            ]) ?>

            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> Оставьте поля дат пустыми, если скидка должна действовать постоянно.
            </div>
        </div>
    </div>

    <!-- Блок SEO -->
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">SEO настройки</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?= Html::button('Автогенерация SEO', ['class' => 'btn btn-info', 'id' => 'generate-seo']) ?>
                </div>
            </div>
            <br>
            <?= $form->field($model, 'seo_url')->textInput(['maxlength' => true, 'id' => 'product-seo_url']) ?>
            <?= $form->field($model, 'meta_title')->textInput(['maxlength' => true, 'id' => 'product-meta_title']) ?>
            <?= $form->field($model, 'meta_description')
                ->textarea(['rows' => 3])
                ->hint('Описание страницы для поисковых систем (до 450 символов)') ?>
            <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true, 'id' => 'product-meta_keywords']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$css = <<<CSS
    .product-form {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0,0,0,0.125);
    }
    
    .card-header h5 {
        color: #333;
    }
    
    .alert-info {
        margin-top: 15px;
        margin-bottom: 0;
    }
CSS;
$this->registerCss($css);
?>

<?php
$js = <<<JS
$(document).ready(function() {
    $('#generate-seo').on('click', function() {
        var productName = $('#product-name').val();
        var productDescription = $('#product-description').val();
        
        $.ajax({
            url: '/Online_shop/web/admin/generate-seo',
            type: 'POST',
            data: {
                name: productName,
                description: productDescription,
                _csrf: yii.getCsrfToken()
            },
            success: function(response) {
                if (response.success) {
                    $('#product-seo_url').val(response.seo_url);
                    $('#product-meta_title').val(response.meta_title);
                    $('#product-meta_description').val(response.meta_description);
                    $('#product-meta_keywords').val(response.meta_keywords);
                } else {
                    alert(response.message || 'Ошибка при генерации SEO');
                }
            },
            error: function() {
                alert('Произошла ошибка при отправке запроса');
            }
        });
    });
});
JS;
$this->registerJs($js);
?>
