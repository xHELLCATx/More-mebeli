<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель заказа
 * Управляет заказами пользователей в интернет-магазине
 *
 * @property int $id ID заказа
 * @property int $user_id ID пользователя
 * @property float $total_sum Общая сумма заказа
 * @property string $status Статус заказа (new, processing, completed, cancelled)
 * @property string $created_at Дата создания
 * @property string $phone Телефон
 * @property string $delivery_address Адрес доставки
 * @property string $comment Комментарий к заказу
 * @property-read User $user Связь с моделью пользователя
 * @property-read OrderItem[] $orderItems Связь с элементами заказа
 */
class Order extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['user_id', 'total_sum', 'phone', 'delivery_address'], 'required'],
            // Целочисленные значения
            [['user_id'], 'integer'],
            // Числовые значения
            [['total_sum'], 'number'],
            // Безопасные атрибуты
            [['created_at'], 'safe'],
            // Строковые поля
            [['status', 'phone', 'delivery_address', 'comment'], 'string'],
            // Проверка статуса заказа
            [['status'], 'in', 'range' => ['new', 'processing', 'completed', 'cancelled']],
            // Значение по умолчанию для статуса
            [['status'], 'default', 'value' => 'new'],
            // Ограничение длины телефона
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * Названия полей для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'total_sum' => 'Сумма заказа',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'phone' => 'Телефон',
            'delivery_address' => 'Адрес доставки',
            'comment' => 'Комментарий к заказу'
        ];
    }

    /**
     * Получает все элементы заказа
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }

    /**
     * Получает пользователя, сделавшего заказ
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Сохраняет элементы заказа из корзины
     * @param Cart[] $cartItems Элементы корзины
     */
    public function saveOrderItems($cartItems)
    {
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $this->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            $orderItem->save();
        }
    }

    /**
     * Действия перед сохранением модели
     * Устанавливает даты создания и обновления
     * @param boolean $insert true для новой записи
     * @return boolean Результат выполнения
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            $this->updated_at = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
