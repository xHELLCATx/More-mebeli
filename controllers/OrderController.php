<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Order;
use app\models\User;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;

/**
 * Контроллер управления заказами
 * Обеспечивает функционал создания, просмотра и управления заказами
 */
class OrderController extends Controller
{
    /**
     * Настройка прав доступа и методов запросов
     * Определяет права доступа для разных типов пользователей
     * и допустимые HTTP-методы для каждого действия
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['admin-orders', 'delete', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->identity;
                            return $user->isAdmin() || $user->isOwner();
                        }
                    ],
                    [
                        'actions' => ['create', 'view', 'user-orders'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['get', 'post'],
                    'view' => ['get'],
                    'admin-orders' => ['get'],
                    'delete' => ['post'],
                    'update' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * Создание нового заказа
     * @return mixed Представление формы заказа или перенаправление после создания
     */
    public function actionCreate()
    {
        $cart = Yii::$app->session->get('cart', []);
        
        if (empty($cart)) {
            return $this->redirect(['/cart/index']);
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = new Order();
        $order->user_id = Yii::$app->user->id;
        $order->total_sum = $total;
        
        if ($order->load(Yii::$app->request->post()) && $order->save()) {
            $orderItems = [];
            foreach ($cart as $item) {
                $orderItem = new \app\models\OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
                $orderItem->save();
                $orderItems[] = [
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ];
            }
            
            // Отправляем уведомление о новом заказе
            $user = Yii::$app->user->identity;
            $orderData = [
                'order_id' => $order->id,
                'customer_name' => $user->username,
                'customer_phone' => $order->phone,
                'customer_email' => $user->email,
                'delivery_address' => $order->delivery_address,
                'comment' => $order->comment,
                'created_at' => $order->created_at,
                'status' => $order->status,
                'items' => $orderItems,
                'total_amount' => $order->total_sum
            ];
            
            Yii::$app->orderBot->sendOrderNotification($orderData);
            
            Yii::$app->session->remove('cart');
            
            return $this->redirect(['view', 'id' => $order->id]);
        }

        return $this->render('create', [
            'model' => $order,
            'cartItems' => $cart,
            'total' => $total,
        ]);
    }

    /**
     * Просмотр заказа
     * @param $id ID заказа
     * @return mixed Представление заказа
     */
    public function actionView($id)
    {
        if (Yii::$app->user->identity->isAdmin()) {
            $order = Order::findOne($id);
        } else {
            $order = Order::findOne([
                'id' => $id,
                'user_id' => Yii::$app->user->id
            ]);
        }
        
        if ($order === null) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        return $this->render('view', [
            'model' => $order,
        ]);
    }

    /**
     * Список заказов пользователя
     * @return mixed Представление списка заказов
     */
    public function actionUserOrders()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('user-orders', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Список всех заказов (для администраторов)
     * @return mixed Представление списка заказов
     */
    public function actionAdminOrders()
    {
        $user = Yii::$app->user->identity;
        if (!$user->isAdmin() && !$user->isOwner()) {
            throw new ForbiddenHttpException('Доступ запрещен.');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('admin-orders', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Удаление заказа
     * @param $id ID заказа
     * @return mixed Перенаправление после удаления
     */
    public function actionDelete($id)
    {
        $order = Order::findOne($id);
        
        if ($order === null) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            \app\models\OrderItem::deleteAll(['order_id' => $id]);
            
            $order->delete();

            $transaction->commit();
            
            return $this->redirect(['admin-orders']);
        } catch (\Exception $e) {
            $transaction->rollBack();

            Yii::error('Ошибка при удалении заказа: ' . $e->getMessage());
        }

        return $this->redirect(['admin-orders']);
    }

    /**
     * Обновление заказа
     * @param $id ID заказа
     * @return mixed Представление формы обновления или перенаправление после обновления
     */
    public function actionUpdate($id)
    {
        // Отключаем кэширование для этой страницы
        Yii::$app->response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        Yii::$app->response->headers->set('Pragma', 'no-cache');
        Yii::$app->response->headers->set('Expires', '0');

        $order = Order::findOne($id);
        
        if ($order === null) {
            throw new NotFoundHttpException('Заказ не найден');
        }

        $currentPhone = $order->phone;
        $currentAddress = $order->delivery_address;
        $currentComment = $order->comment;

        if ($order->load(Yii::$app->request->post())) {
            $order->phone = $currentPhone;
            $order->delivery_address = $currentAddress;
            $order->comment = $currentComment;
            
            if ($order->save()) {
                // Получаем все товары заказа
                $orderItems = [];
                foreach ($order->orderItems as $item) {
                    $orderItems[] = [
                        'name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                }

                // Отправляем уведомление об обновлении заказа
                $user = User::findOne($order->user_id);
                $orderData = [
                    'order_id' => $order->id,
                    'customer_name' => $user->username,
                    'customer_phone' => $order->phone,
                    'customer_email' => $user->email,
                    'delivery_address' => $order->delivery_address,
                    'comment' => $order->comment,
                    'created_at' => $order->created_at,
                    'status' => $order->status,
                    'items' => $orderItems,
                    'total_amount' => $order->total_sum,
                    'is_update' => true
                ];
                
                Yii::$app->orderBot->sendOrderNotification($orderData);
                
                return $this->redirect(['admin-orders']);
            }
        }

        return $this->render('update', [
            'model' => $order,
        ]);
    }
}
