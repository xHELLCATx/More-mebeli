<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель элемента заказа
 * Представляет отдельные товары в заказе
 *
 * @property int $id ID элемента заказа
 * @property int $order_id ID заказа
 * @property int $product_id ID товара
 * @property int $quantity Количество
 * @property float $price Цена за единицу
 * @property string $created_at Дата создания
 * @property-read Order $order Связь с моделью заказа
 * @property-read Product $product Связь с моделью товара
 */
class OrderItem extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'order_items';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['order_id', 'product_id', 'quantity', 'price'], 'required'],
            // Целочисленные значения
            [['order_id', 'product_id', 'quantity'], 'integer'],
            // Числовые значения
            ['price', 'number'],
            // Безопасные атрибуты
            ['created_at', 'safe'],
        ];
    }

    /**
     * Названия полей для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'ID заказа',
            'product_id' => 'ID товара',
            'quantity' => 'Количество',
            'price' => 'Цена',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * Получает заказ, к которому относится элемент
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    /**
     * Получает товар, связанный с элементом заказа
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Действия перед сохранением модели
     * Устанавливает дату создания для новых записей
     * @param boolean $insert true для новой записи
     * @return boolean Результат выполнения
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = date('Y-m-d H:i:s');
            }
            return true;
        }
        return false;
    }
}
