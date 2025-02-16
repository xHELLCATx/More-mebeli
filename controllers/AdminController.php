<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use app\models\Product;
use app\models\User;
use yii\web\UploadedFile;
use app\models\ProductImage;
use app\models\Order;
use app\models\Settings;
use app\models\PageSeo;

/**
 * Контроллер административной панели
 * Обеспечивает функционал управления магазином для администраторов
 */
class AdminController extends Controller
{
    /**
     * Настройка поведения контроллера
     * Ограничивает доступ только для пользователей с ролями admin и owner
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
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->identity;
                            return $user->role === 'owner' || $user->role === 'admin';
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException('Доступ запрещен. Только для администраторов.');
                }
            ],
        ];
    }

    /**
     * Главная страница админ-панели
     * Отображает основную статистику магазина
     * @return string Представление главной страницы админ-панели
     */
    public function actionIndex()
    {
        $userCount = User::find()->count();
        $productCount = Product::find()->count();
        $orderCount = Order::find()->count();
        $revenue = Order::find()
            ->where(['status' => 'completed'])
            ->sum('total_sum');

        return $this->render('index', [
            'userCount' => $userCount,
            'productCount' => $productCount,
            'orderCount' => $orderCount,
            'revenue' => $revenue ?? 0,
        ]);
    }

    /**
     * Отображает список всех товаров
     * @return string Представление со списком товаров и общей статистикой
     */
    public function actionProducts()
    {
        $products = Product::find()->all();
        $totalCount = Product::find()->count();
        
        return $this->render('products', [
            'products' => $products,
            'totalCount' => $totalCount
        ]);
    }

    /**
     * Создание нового товара
     * Загружает изображения товара и сохраняет их в директории uploads
     * @return string|Response Форма создания товара или перенаправление после успешного создания
     */
    public function actionCreateProduct()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $model->additionalImages = UploadedFile::getInstances($model, 'additionalImages');
            
            if ($model->validate()) {
                if ($model->imageFile) {
                    $uploadPath = Yii::getAlias('@webroot/uploads/');
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $fileName = time() . '_' . $model->imageFile->baseName . '.' . $model->imageFile->extension;
                    $filePath = $uploadPath . $fileName;
                    
                    if ($model->imageFile->saveAs($filePath)) {
                        $model->image = $fileName;
                    }
                }


                if ($model->save(false)) {
                    if ($model->additionalImages) {
                        foreach ($model->additionalImages as $file) {
                            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
                            $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;
                            
                            if ($file->saveAs($filePath)) {
                                $productImage = new ProductImage([
                                    'product_id' => $model->id,
                                    'image' => $fileName
                                ]);
                                $productImage->save();
                            }
                        }
                    }

                    return $this->redirect(['products']);
                }
            }
        }

        return $this->render('product-form', [
            'model' => $model,
            'title' => 'Создание товара'
        ]);
    }

    /**
     * Обновление существующего товара
     * @param integer $id ID товара
     * @return string|Response Форма редактирования или перенаправление после успешного обновления
     * @throws NotFoundHttpException если товар не найден
     */
    public function actionUpdateProduct($id)
    {
        $model = Product::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Товар не найден.');
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $model->additionalImages = UploadedFile::getInstances($model, 'additionalImages');
            
            if ($model->validate()) {
                if ($model->imageFile) {
                    $uploadPath = Yii::getAlias('@webroot/uploads/');
                    $fileName = time() . '_' . $model->imageFile->baseName . '.' . $model->imageFile->extension;
                    $filePath = $uploadPath . $fileName;
                    
                    if ($model->imageFile->saveAs($filePath)) {
                        if ($model->image && file_exists($uploadPath . $model->image)) {
                            unlink($uploadPath . $model->image);
                        }
                        $model->image = $fileName;
                    }
                }
                if ($model->save(false)) {
                    if ($model->additionalImages) {
                        foreach ($model->additionalImages as $file) {
                            $fileName = time() . '_' . uniqid() . '.' . $file->extension;
                            $filePath = Yii::getAlias('@webroot/uploads/') . $fileName;
                            
                            if ($file->saveAs($filePath)) {
                                $productImage = new ProductImage([
                                    'product_id' => $model->id,
                                    'image' => $fileName
                                ]);
                                $productImage->save();
                            }
                        }
                    }

                    return $this->redirect(['products']);
                }
            }
        }

        return $this->render('product-form', [
            'model' => $model,
            'title' => 'Редактирование товара'
        ]);
    }

    /**
     * Удаление товара
     * @param integer $id ID товара
     * @return Response Перенаправление на список товаров
     */
    public function actionDeleteProduct($id)
    {
        $model = Product::findOne($id);
        if ($model && $model->delete()) {
            return $this->redirect(['products']);
        }
    }

    /**
     * Удаление дополнительного изображения товара
     * @param integer $id ID изображения
     * @return Response Перенаправление на предыдущую страницу
     */
    public function actionDeleteImage($id)
    {
        $image = ProductImage::findOne($id);
        if ($image) {
            $filePath = Yii::getAlias('@webroot/uploads/') . $image->image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $image->delete();
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Отображает список всех пользователей
     * Включает статистику по ролям пользователей
     * @return string Представление со списком пользователей
     */
    public function actionUsers()
    {
        $users = User::find()->all();
        $totalCount = count($users);
    
        $adminCount = User::find()->where(['role' => 'admin'])->count();
        $ownerCount = User::find()->where(['role' => 'owner'])->count();

        return $this->render('users', [
            'users' => $users,
            'totalCount' => $totalCount,
            'adminCount' => $adminCount,
            'ownerCount' => $ownerCount,
        ]);
    }

    /**
     * Обновление данных пользователя
     * @param integer $id ID пользователя
     * @return string|Response Форма редактирования или перенаправление после успешного обновления
     * @throws NotFoundHttpException если пользователь не найден
     * @throws ForbiddenHttpException если нет прав на редактирование
     */
    public function actionUpdateUser($id)
    {
        $model = User::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Пользователь не найден.');
        }

        $currentUser = Yii::$app->user->identity;
        if ($currentUser->role !== 'owner') {
            throw new ForbiddenHttpException('Только владелец может редактировать пользователей.');
        }

        if ($model->role === 'owner' && $model->id !== $currentUser->id) {
            throw new ForbiddenHttpException('Владелец не может быть изменен.');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($currentUser->role !== 'owner' && $model->isAttributeChanged('role')) {
                throw new ForbiddenHttpException('Только владелец может менять роли пользователей.');
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно обновлен.');
                return $this->redirect(['users']);
            }
        }

        return $this->render('user-form', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление пользователя
     * @param integer $id ID пользователя
     * @return Response Перенаправление на список пользователей
     */
    public function actionDeleteUser($id)
    {
        $model = User::findOne($id);
        if ($model && $model->delete()) {
            return $this->redirect(['users']);
        }
    }

    /**
     * Отображает список всех заказов
     * @return string Представление со списком заказов
     */
    public function actionOrders()
    {
        $orders = Order::find()
            ->with(['user', 'orderItems', 'orderItems.product'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('orders', [
            'orders' => $orders,
        ]);
    }

    /**
     * Обновление статуса заказа
     * @param integer $id ID заказа
     * @return string|Response Форма обновления статуса или перенаправление после успешного обновления
     * @throws NotFoundHttpException если заказ не найден
     */
    public function actionUpdateOrderStatus($id)
    {
        $order = Order::findOne($id);
        if (!$order) {
            throw new NotFoundHttpException('Заказ не найден.');
        }

        if ($order->load(Yii::$app->request->post()) && $order->save()) {
            return $this->redirect(['orders']);
        }

        return $this->render('update-order-status', [
            'order' => $order,
        ]);
    }

    /**
     * Управление настройками магазина
     * @return string Представление с формой настроек
     */
    public function actionSettings()
    {
        $settings = Settings::find()->all();

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            foreach ($post['Settings'] as $key => $value) {
                Settings::setValue($key, $value);
            }
            return $this->refresh();
        }

        return $this->render('settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Отображает список всех SEO-страниц
     * @return string Представление со списком SEO-страниц
     */
    public function actionSeoPages()
    {
        $query = PageSeo::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('seo-pages', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Создание новой SEO-страницы
     * @return string|Response Форма создания или перенаправление после успешного создания
     */
    public function actionCreatePageSeo()
    {
        $model = new PageSeo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'SEO данные успешно добавлены');
            return $this->redirect(['seo-pages']);
        }

        return $this->render('page-seo-form', [
            'model' => $model,
            'title' => 'Добавление SEO данных',
        ]);
    }

    /**
     * Обновление SEO-данных страницы
     * @param integer $id ID SEO-страницы
     * @return string|Response Форма редактирования или перенаправление после успешного обновления
     * @throws NotFoundHttpException если страница не найдена
     */
    public function actionUpdatePageSeo($id)
    {
        $model = PageSeo::findOne($id);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'SEO данные успешно обновлены');
            return $this->redirect(['seo-pages']);
        }

        return $this->render('page-seo-form', [
            'model' => $model,
            'title' => 'Редактирование SEO данных',
        ]);
    }

    /**
     * Удаление SEO-страницы
     * @param integer $id ID SEO-страницы
     * @return Response Перенаправление на список SEO-страниц
     * @throws NotFoundHttpException если страница не найдена
     */
    public function actionDeletePageSeo($id)
    {
        $model = PageSeo::findOne($id);
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('Страница не найдена.');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'SEO данные успешно удалены');
        return $this->redirect(['seo-pages']);
    }

    /**
     * Инициализация SEO-данных по умолчанию
     * Создает или обновляет SEO-данные для основных страниц сайта
     * @return Response Перенаправление на список SEO-страниц
     */
    public function actionInitDefaultSeo()
    {
        $defaultPages = [
            '/site/index' => [
                'page_title' => 'Главная',
                'meta_title' => 'Интернет-магазин мебели | Главная',
                'meta_description' => 'Широкий выбор качественной мебели для вашего дома. Доступные цены, быстрая доставка.',
                'meta_keywords' => 'мебель, купить мебель, интернет-магазин мебели'
            ],
            '/site/contact' => [
                'page_title' => 'Контакты',
                'meta_title' => 'Контакты | Интернет-магазин мебели',
                'meta_description' => 'Свяжитесь с нами. Контактная информация, адрес и телефоны нашего магазина мебели.',
                'meta_keywords' => 'контакты, адрес магазина, телефон, связаться'
            ],
            '/site/about' => [
                'page_title' => 'О нас',
                'meta_title' => 'О нас | Интернет-магазин мебели',
                'meta_description' => 'Узнайте больше о нашем магазине мебели. История, миссия и ценности компании.',
                'meta_keywords' => 'о нас, магазин мебели, компания'
            ],
            '/site/catalog' => [
                'page_title' => 'Каталог',
                'meta_title' => 'Каталог мебели | Интернет-магазин',
                'meta_description' => 'Полный каталог мебели с ценами и описаниями. Стулья, столы, кровати и другая мебель.',
                'meta_keywords' => 'каталог мебели, мебель каталог, купить мебель'
            ]
        ];

        foreach ($defaultPages as $url => $data) {
            $model = PageSeo::findOne(['page_url' => $url]) ?? new PageSeo();
            $model->page_url = $url;
            $model->attributes = $data;
            $model->save();
        }

        Yii::$app->session->setFlash('success', 'SEO данные по умолчанию успешно добавлены');
        return $this->redirect(['seo-pages']);
    }

    /**
     * Отображает страницу для отладки SEO-данных
     * @return string Представление страницы отладки SEO-данных
     */
    public function actionSeoDebug()
    {
        return $this->render('seo-debug');
    }
}
