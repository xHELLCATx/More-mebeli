<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\RecentlyViewed;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Контроллер недавно просмотренных товаров
 * Управляет отображением и очисткой истории просмотров пользователя
 */
class RecentlyViewedController extends Controller
{
    /**
     * Настройка прав доступа
     * Разрешает доступ только авторизованным пользователям
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Отображает список недавно просмотренных товаров
     * @return string Представление со списком товаров
     */
    public function actionIndex()
    {
        $query = RecentlyViewed::find()
            ->with('product')
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['viewed_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 6,
                'defaultPageSize' => 6,
                'pageSizeLimit' => [6, 18],
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Очищает историю просмотренных товаров
     * @return Response Перенаправление на страницу истории
     */
    public function actionClearHistory()
    {
        if (Yii::$app->request->isPost) {
            RecentlyViewed::clearHistory(Yii::$app->user->id);
            Yii::$app->session->setFlash('success', 'История просмотров очищена');
            return $this->redirect(['index']);
        }
        return $this->redirect(['index']);
    }
}
