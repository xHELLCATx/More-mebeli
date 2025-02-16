<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\TextFormatter;

$cardContent = '
<div class="card h-100">
    ' . ($model->image ? '<img class="card-img-top" style="height: 500px; object-fit: cover;" src="' . Url::to('@web/' . $model->image) . '" alt="' . Html::encode($model->img_alt) . '" title="' . Html::encode($model->img_title) . '">' : '') . '
    
    <div class="card-body">
        <h2>' . Html::encode($model->title) . '</h2>
        <p>' . mb_substr(TextFormatter::formatCardText($model->content), 0, 200) . '...</p>
        <div class="article-meta">
            Автор: ' . ($model->user ? Html::encode($model->user->username) : 'Неизвестен') . '<br>
            Создано: ' . Yii::$app->formatter->asDatetime($model->created_at) . '
        </div>
    </div>
</div>';

echo Html::a($cardContent, ['view', 'seo_url' => $model->seo_url], ['class' => 'text-decoration-none text-dark']);
?>
