<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель изображения товара
 * Управляет дополнительными изображениями товаров
 *
 * @property int $id ID изображения
 * @property int $product_id ID товара
 * @property string $image Путь к файлу изображения
 * @property int $sort_order Порядок сортировки
 * @property-read Product $product Связь с моделью товара
 */
class ProductImage extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'product_images';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['product_id', 'image'], 'required'],
            // Целочисленные значения
            [['product_id', 'sort_order'], 'integer'],
            // Ограничение длины пути к изображению
            [['image'], 'string', 'max' => 255],
            // Проверка существования товара
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
        ];
    }

    /**
     * Получает товар, к которому относится изображение
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}
