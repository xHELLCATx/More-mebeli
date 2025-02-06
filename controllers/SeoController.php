<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundException;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\PageSeo;
use app\models\Product;


/**
 * Контроллер управления SEO-параметрами
 * Обеспечивает функционал управления мета-тегами и SEO-настройками страниц
 */
class SeoController extends Controller
{
    /**
     * Настройка прав доступа
     * Разрешает доступ только администраторам и владельцу
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'owner'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображает список всех страниц с SEO-настройками
     * @return string
     */
    public function actionIndex()
    {
        $pages = PageSeo::find()->all();
        return $this->render('index', [
            'pages' => $pages
        ]);
    }

    /**
     * Обновление SEO-параметров страницы
     * @param integer $id ID страницы
     * @return mixed
     * @throws NotFoundException если страница не найдена
     */
    public function actionUpdatePage($id)
    {
        $model = PageSeo::findOne($id);
        if (!$model) {
            throw new NotFoundException('Страница не найдена.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'SEO данные успешно обновлены');
            return $this->redirect(['index']);
        }

        return $this->render('update-page', [
            'model' => $model
        ]);
    }

    public function actionInitDefault()
    {
        $defaultSeoData = [
            '/site/index' => [
                'page_title' => 'Главная',
                'meta_title' => 'Интернет-магазин мебели | Главная',
                'meta_description' => 'Широкий выбор качественной мебели для вашего дома. Доступные цены, быстрая доставка.',
                'meta_keywords' => 'мебель, купить мебель, интернет-магазин мебели'
            ],
            // Добавить другие страницы по необходимости
        ];

        foreach ($defaultSeoData as $url => $data) {
            $model = PageSeo::findOne(['page_url' => $url]) ?? new PageSeo();
            $model->page_url = $url;
            $model->attributes = $data;
            $model->save();
        }

        Yii::$app->session->setFlash('success', 'SEO данные по умолчанию успешно добавлены');
        return $this->redirect(['index']);
    }
}
