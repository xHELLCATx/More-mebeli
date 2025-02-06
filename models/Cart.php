<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель корзины покупок
 * Управляет хранением и обработкой товаров в корзине пользователя
 */
class Cart extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'quantity'], 'required'],
            [['user_id', 'product_id', 'quantity'], 'integer'],
            [['quantity'], 'default', 'value' => 1],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * Связь с моделью пользователя
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Связь с моделью товара
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Получает все товары в корзине текущего пользователя
     * @return Cart[] Массив элементов корзины с предзагруженными товарами
     */
    public static function getCartItems()
    {
        return self::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('product')
            ->all();
    }

    /**
     * Подсчитывает общую стоимость всех товаров в корзине
     * @return float Общая сумма
     */
    public static function getTotal()
    {
        $total = 0;
        $items = self::getCartItems();
        foreach ($items as $item) {
            $total += $item->product->price * $item->quantity;
        }
        return $total;
    }

    /**
     * Добавляет товар в корзину или увеличивает его количество
     * @param integer $productId ID товара
     * @param integer $quantity Количество товара (по умолчанию 1)
     * @return boolean Результат операции
     */
    public static function addToCart($productId, $quantity = 1)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $userId = Yii::$app->user->id;

        $cartItem = self::find()
            ->where(['user_id' => $userId, 'product_id' => $productId])
            ->one();
        
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            return $cartItem->save();
        } else {
            $cartItem = new self();
            $cartItem->user_id = $userId;
            $cartItem->product_id = $productId;
            $cartItem->quantity = $quantity;
            return $cartItem->save();
        }
    }

    /**
     * Изменяет количество товара в корзине
     * @param integer $id ID записи корзины
     * @param string $action Действие ('increase' или 'decrease')
     * @return boolean Результат операции
     */
    public static function updateQuantity($id, $action)
    {
        $cartItem = self::findOne($id);
        if (!$cartItem || $cartItem->user_id !== Yii::$app->user->id) {
            return false;
        }

        if ($action === 'increase') {
            $cartItem->quantity++;
        } elseif ($action === 'decrease') {
            if ($cartItem->quantity > 1) {
                $cartItem->quantity--;
            } else {
                return $cartItem->delete();
            }
        }

        return $cartItem->save();
    }

    /**
     * Подсчитывает общее количество товаров в корзине
     * @return integer Общее количество товаров
     */
    public static function getTotalCount()
    {
        $total = 0;
        $items = self::getCartItems();
        foreach ($items as $item) {
            $total += $item->quantity;
        }
        return $total;
    }

    /**
     * Очищает корзину текущего пользователя
     * @return boolean Результат операции
     */
    public static function clearCart()
    {
        return self::deleteAll(['user_id' => Yii::$app->user->id]);
    }
}
