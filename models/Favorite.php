<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель избранных товаров
 * Обеспечивает связь между пользователями и их избранными товарами
 *
 * @property int $user_id ID пользователя
 * @property int $product_id ID товара
 * @property User $user Связь с моделью пользователя
 * @property Product $product Связь с моделью товара
 */
class Favorite extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'favorites';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['user_id', 'product_id'], 'required'],
            // Целочисленные значения
            [['user_id', 'product_id'], 'integer'],
            // Проверка существования пользователя
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            // Проверка существования товара
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
}
