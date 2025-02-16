<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="article-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput([
        'maxlength' => true,
        'class' => 'form-control form-control-lg mb-3',
        'placeholder' => 'Введите название статьи'
    ]) ?>

    <?= $form->field($model, 'content')->textarea([
        'rows' => 15,
        'id' => 'article-content-editor'
    ])->label(false) ?>

    <?php if ($model->image): ?>
        <div class="mb-3">
            <label class="form-label">Текущее изображение</label>
            <div>
                <img src="<?= Yii::getAlias('@web/' . $model->image) ?>" alt="" style="max-width: 300px; margin-bottom: 10px;" class="img-thumbnail">
            </div>
            <div class="form-text text-muted">
                Загрузите новое изображение, только если хотите заменить текущее
            </div>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'image')->fileInput([
        'class' => 'form-control',
        'accept' => 'image/*'
    ])->hint('Поддерживаемые форматы: PNG, JPG, JPEG') ?>

    <div class="form-group mt-4">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success btn-lg px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js', ['referrerpolicy' => 'origin']);

$js = <<<JS
window.addEventListener('load', function() {
    tinymce.init({
        selector: '#article-content-editor',
        height: 500,
        language: 'ru',
        language_url: 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/langs/ru.js',
        menubar: 'file edit view insert format tools table help',
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
        ],
        toolbar: 'undo redo | styles | bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | table link image emoticons | removeformat help',
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
                font-size: 16px;
                max-width: 100%;
            }
            table {
                width: 100%;
                margin: 0.5rem 0;
                border-collapse: collapse;
            }
            table td, table th {
                padding: 0.75rem;
                border: 1px solid #dee2e6;
                vertical-align: top;
            }
            table tr:nth-child(even) {
                background-color: rgba(0, 0, 0, 0.02);
            }
            p:empty { 
                display: none;
            }
            p { 
                margin: 0;
                padding: 0;
            }
        `,
        table_default_attributes: {
            border: '1'
        },
        table_default_styles: {
            'border-collapse': 'collapse',
            'width': '100%'
        },
        table_responsive_width: true,
        table_sizing_mode: 'relative',
        promotion: false,
        branding: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        forced_root_block: false,
        remove_trailing_brs: true,
        menu: {
            file: { title: 'Файл', items: 'newdocument restoredraft | preview | print' },
            edit: { title: 'Правка', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
            view: { title: 'Вид', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
            insert: { title: 'Вставка', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
            format: { title: 'Формат', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align | forecolor backcolor | removeformat' },
            tools: { title: 'Инструменты', items: 'spellchecker spellcheckerlanguage | code wordcount' },
            table: { title: 'Таблица', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' }
        },
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
});
JS;

$this->registerJs($js);
?>

<?php
$css = <<<CSS
.tox-tinymce {
    border-radius: 0.25rem;
    margin-bottom: 1rem;
}
.article-form {
    max-width: 1200px;
    margin: 0 auto;
}
.tox .tox-toolbar__group {
    padding: 0 5px !important;
}
CSS;

$this->registerCss($css);
?>
