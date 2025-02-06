<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use yii\web\Response;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\filters\VerbFilter;

/**
 * Контроллер корзины покупок
 * Обеспечивает функционал работы с корзиной товаров
 */
class CartController extends Controller
{
    /**
     * Настройка поведения контроллера
     * Определяет допустимые HTTP-методы для действий
     * @return array Конфигурация поведения
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'add' => ['post'],
                    'remove' => ['post'],
                    'clear' => ['post'],
                    'update-quantity' => ['post'],
                    'count' => ['get'],
                ],
            ],
        ];
    }

    /**
     * Отображает содержимое корзины
     * Подсчитывает общую стоимость товаров
     * @return string Представление корзины с товарами
     */
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $cart = $session->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $this->render('index', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Добавление товара в корзину
     * Обрабатывает AJAX-запрос на добавление товара
     * @return array Результат операции в формате JSON
     */
    public function actionAdd()
    {
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['site/catalog']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        
        if (!$id) {
            return [
                'success' => false,
                'message' => 'Не указан ID товара'
            ];
        }

        $product = Product::findOne($id);
        if ($product === null) {
            return [
                'success' => false,
                'message' => 'Товар не найден'
            ];
        }

        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $id,
                'name' => $product->name,
                'price' => $product->hasValidDiscount() ? $product->getDiscountedPrice() : $product->price,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        $session->set('cart', $cart);

        $totalCount = 0;
        foreach ($cart as $item) {
            $totalCount += $item['quantity'];
        }

        return [
            'success' => true,
            'count' => $totalCount,
            'html' => '
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    <span>Товар добавлен в корзину</span>
                    <a href="' . Url::to(['/cart/index']) . '" class="btn btn-outline-success btn-sm mx-2">
                        <i class="fas fa-shopping-cart"></i> Перейти в корзину
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>'
        ];
    }

    /**
     * Обновление количества товара в корзине
     * @return array Результат операции в формате JSON
     */
    public function actionUpdateQuantity()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        $quantity = (int)Yii::$app->request->post('quantity');
        
        if (!$id || $quantity < 1) {
            return [
                'success' => false,
                'message' => 'Неверные параметры'
            ];
        }

        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            $session->set('cart', $cart);
            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'message' => 'Товар не найден в корзине'
        ];
    }

    /**
     * Удаление товара из корзины
     * @param integer $id ID товара
     * @return Response Перенаправление на страницу корзины
     */
    public function actionRemove($id)
    {
        $session = Yii::$app->session;
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $session->set('cart', $cart);
        }

        return $this->redirect(['index']);
    }

    /**
     * Очистка корзины
     * Удаляет все товары из корзины
     * @return Response Перенаправление на страницу корзины
     */
    public function actionClear()
    {
        $session = Yii::$app->session;
        $session->remove('cart');
        return $this->redirect(['index']);
    }

    /**
     * Получение количества товаров в корзине
     * Используется для AJAX-обновления счетчика корзины
     * @return array Количество товаров в формате JSON
     */
    public function actionCount()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $session = Yii::$app->session;
        $cart = $session->get('cart', []);
        
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        
        return [
            'count' => $count
        ];
    }
}
