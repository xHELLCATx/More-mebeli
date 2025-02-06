<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegisterForm;
use app\models\Product;
use app\models\Category;
use app\models\Cart;
use app\models\Favorite;
use yii\data\Pagination;
use app\models\RecentlyViewed;

/**
 * Основной контроллер сайта
 * Обрабатывает главные страницы и базовый функционал магазина
 */
class SiteController extends Controller
{
    /**
     * @var Product|null Текущий просматриваемый продукт
     */
    public $product = null;

    /**
     * Настройка поведения контроллера
     * Определяет правила доступа и допустимые HTTP-методы
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Настройка действий контроллера
     * Определяет действия, которые могут быть вызваны извне
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Отображает главную страницу сайта
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Действие авторизации пользователя
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Действие выхода пользователя из системы
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Отображает страницу контактов
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Отображает страницу "О нас"
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Отображает страницу каталога товаров
     *
     * @return string
     */
    public function actionCatalog()
    {
        $query = Product::find();
        
        $category = Yii::$app->request->get('category');
        $priceFrom = Yii::$app->request->get('price_from');
        $priceTo = Yii::$app->request->get('price_to');
        $searchQuery = Yii::$app->request->get('search');
        
        if ($searchQuery) {
            $query->andWhere(['like', 'name', $searchQuery]);
        }
        if ($category) {
            $query->andWhere(['category' => $category]);
        }
        if ($priceFrom) {
            $query->andWhere(['>=', 'price', $priceFrom]);
        }
        if ($priceTo) {
            $query->andWhere(['<=', 'price', $priceTo]);
        }

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 9,
            'pageSizeParam' => false, 
        ]);

        $products = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        $categories = Product::find()
            ->select('category')
            ->distinct()
            ->column();
            
        return $this->render('catalog', [
            'products' => $products,
            'pages' => $pages,
            'categories' => $categories,
            'selectedCategory' => $category,
            'priceFrom' => $priceFrom,
            'priceTo' => $priceTo,
            'searchQuery' => $searchQuery
        ]);
    }

    /**
     * Действие регистрации пользователя
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->register()) {
                Yii::$app->session->setFlash('success', 'Регистрация успешно завершена. Теперь вы можете войти в систему.');
                return $this->redirect(['site/login']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Отображает страницу товара
     *
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionProduct($id)
    {
        $product = Product::findOne($id);
        if ($product === null) {
            throw new NotFoundHttpException('The requested product does not exist.');
        }

        // Сохраняем продукт в контексте контроллера для доступа в шаблоне
        $this->product = $product;

        if (!Yii::$app->user->isGuest) {
            RecentlyViewed::addToRecentlyViewed(Yii::$app->user->id, $id);
        }

        return $this->render('product', [
            'product' => $product,
        ]);
    }

    /**
     * Отображает страницу профиля пользователя
     *
     * @return string
     */
    public function actionProfile()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['login']);
        }

        $user = Yii::$app->user->identity;
        $cartItems = Cart::find()
            ->where(['user_id' => $user->id])
            ->with('product')
            ->all();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return $this->render('profile', [
            'user' => $user,
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    /**
     * Действие смены пароля пользователя
     *
     * @return Response|string
     */
    public function actionChangePassword()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new \app\models\ChangePasswordForm();

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            return $this->redirect(['profile']);
        }

        return $this->render('change-password', [
            'model' => $model,
        ]);
    }

    /**
     * Добавление/удаление товара из избранного
     * Обрабатывает AJAX-запрос на изменение статуса избранного товара
     * @return array Результат операции в формате JSON
     */
    public function actionToggleFavorite()
    {
        if (Yii::$app->user->isGuest) {
            return $this->asJson(['status' => 'error', 'message' => 'Необходимо авторизоваться']);
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $id = Yii::$app->request->post('id');
        if (!$id) {
            return ['status' => 'error', 'message' => 'Не указан ID товара'];
        }

        $userId = Yii::$app->user->id;
        $favorite = Favorite::findOne(['user_id' => $userId, 'product_id' => $id]);

        if ($favorite) {
            $favorite->delete();
            return ['status' => 'removed'];
        } else {
            $favorite = new Favorite();
            $favorite->user_id = $userId;
            $favorite->product_id = $id;
            if ($favorite->save()) {
                return ['status' => 'added'];
            } else {
                return ['status' => 'error', 'message' => 'Ошибка при сохранении'];
            }
        }
    }

    /**
     * Отображает список избранных товаров пользователя
     * @return string|Response Представление списка избранного или перенаправление на страницу входа
     */
    public function actionFavorites()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        $favorites = Favorite::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->all();

        return $this->render('favorites', [
            'favorites' => $favorites,
        ]);
    }
}
