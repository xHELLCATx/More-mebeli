<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель просмотренных товаров
 * Отслеживает и управляет историей просмотров товаров пользователями
 *
 * @property int $id ID записи
 * @property int $user_id ID пользователя
 * @property int $product_id ID товара
 * @property string $viewed_at Дата и время просмотра
 * @property-read Product $product Связь с моделью товара
 */
class RecentlyViewed extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'recently_viewed';
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
            // Безопасные атрибуты
            [['viewed_at'], 'safe'],
        ];
    }

    /**
     * Получает связанный товар
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Добавляет товар в список просмотренных
     * Если товар уже был просмотрен, обновляет время просмотра
     * 
     * @param int $userId ID пользователя
     * @param int $productId ID товара
     */
    public static function addToRecentlyViewed($userId, $productId)
    {

        $model = self::find()
            ->where(['user_id' => $userId, 'product_id' => $productId])
            ->one();
        
        if ($model) {

            $model->viewed_at = new \yii\db\Expression('NOW()');
            $model->save();
        } else {

            $model = new self([
                'user_id' => $userId,
                'product_id' => $productId
            ]);
            $model->save();
        }
    }

    /**
     * Получает список последних просмотренных товаров
     * 
     * @param int $userId ID пользователя
     * @param int $limit Максимальное количество товаров
     * @return RecentlyViewed[] Массив просмотренных товаров
     */
    public static function getRecentlyViewed($userId, $limit = 3)
    {
        return self::find()
            ->with('product')
            ->where(['user_id' => $userId])
            ->orderBy(['viewed_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * Очищает историю просмотров пользователя
     * 
     * @param int $userId ID пользователя
     * @return int Количество удаленных записей
     */
    public static function clearHistory($userId)
    {
        return self::deleteAll(['user_id' => $userId]);
    }
}
