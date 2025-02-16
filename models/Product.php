<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Модель товара
 * Управляет данными и поведением товаров в магазине
 *
 * @property int $id ID товара
 * @property string $name Название товара
 * @property string $description Описание товара
 * @property float $price Цена товара
 * @property string $category Категория товара
 * @property string $image_url URL изображения товара
 * @property string $image Основное изображение товара
 * @property float $discount_percent Процент скидки (0-100)
 * @property string $discount_start Дата начала скидки
 * @property string $discount_end Дата окончания скидки
 * @property string $meta_title SEO заголовок
 * @property string $meta_description SEO описание
 * @property string $meta_keywords SEO ключевые слова
 * @property string $seo_url SEO-friendly URL
 * @property string $stock_status Статус наличия товара (много, мало, закончился)
 * @property-read ProductImage[] $productImages Связанные изображения товара
 * @property-read Favorite[] $favorites Избранные товары
 */
class Product extends ActiveRecord
{
    /**
     * @var UploadedFile Загруженный файл основного изображения
     */
    public $imageFile;

    /**
     * @var UploadedFile[] Массив загруженных дополнительных изображений
     */
    public $additionalImages;

    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'products';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['name', 'price'], 'required'],
            // Числовые поля
            [['price'], 'number'],
            // Текстовые поля
            [['description'], 'string'],
            // Ограничения длины строк
            [['category'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['image_url', 'image'], 'string', 'max' => 255],
            // Правила для загрузки изображений
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['additionalImages'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 3],
            // Правила для скидок
            [['discount_percent'], 'number', 'min' => 0, 'max' => 100],
            [['discount_start', 'discount_end'], 'safe'],
            // SEO правила
            [['meta_title', 'meta_description', 'meta_keywords', 'seo_url'], 'string'],
            [['meta_title'], 'string', 'max' => 60],
            [['seo_url'], 'unique'],
            [['seo_url'], 'match', 'pattern' => '/^[a-z0-9-]+$/', 'message' => 'SEO URL может содержать только строчные буквы, цифры и дефисы'],
            // Статус наличия
            [['stock_status'], 'in', 'range' => ['много', 'мало', 'закончился']],
        ];
    }

    /**
     * Дополнительные атрибуты модели
     * @return array Массив атрибутов
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), ['additionalImages']);
    }

    /**
     * Названия полей для отображения
     * @return array Массив меток полей
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'description' => 'Описание',
            'price' => 'Цена',
            'category' => 'Категория',
            'image_url' => 'URL изображения',
            'image' => 'Изображение',
            'imageFile' => 'Изображение',
            'additionalImages' => 'Дополнительные изображения',
            'discount_percent' => 'Процент скидки',
            'discount_start' => 'Дата начала скидки',
            'discount_end' => 'Дата окончания скидки',
            // SEO метки
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'seo_url' => 'SEO URL',
            'stock_status' => 'Наличие товара',
        ];
    }

    /**
     * Действия перед удалением модели
     * Удаляет файл изображения товара с диска
     * @return boolean Результат выполнения
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->image) {
                $imagePath = \Yii::getAlias('@webroot/uploads/') . $this->image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Действия перед сохранением модели
     * @param boolean $insert true для новой записи
     * @return boolean Результат выполнения
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        }
        return false;
    }

    /**
     * Действия после загрузки модели
     */
    public function afterFind()
    {
        parent::afterFind();
    }

    /**
     * Действия после удаления модели
     * Удаляет связанные записи из истории просмотров
     */
    public function afterDelete()
    {
        parent::afterDelete();
        
        \app\models\RecentlyViewed::deleteAll(['product_id' => $this->id]);
        
        return true;
    }

    /**
     * Получает все дополнительные изображения товара
     * @return \yii\db\ActiveQuery
     */
    public function getProductImages()
    {
        return $this->hasMany(ProductImage::class, ['product_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
    }

    /**
     * Получает массив всех изображений товара
     * Включает основное изображение и все дополнительные
     * @return array Массив путей к изображениям
     */
    public function getAllImages()
    {
        $images = [];
        if ($this->image) {
            $images[] = $this->image;
        }
        foreach ($this->productImages as $productImage) {
            $images[] = $productImage->image;
        }
        return $images;
    }

    /**
     * Проверяет, действует ли скидка на товар
     * Учитывает процент скидки и период её действия
     * @return boolean true если скидка действует
     */
    public function hasValidDiscount()
    {
        if (empty($this->discount_percent)) {
            return false;
        }

        $now = new \DateTime();
        $start = $this->discount_start ? new \DateTime($this->discount_start) : null;
        $end = $this->discount_end ? new \DateTime($this->discount_end) : null;

        if (!$start && !$end) {
            return true;
        }

        if ($start && $end) {
            return $now >= $start && $now <= $end;
        }

        if ($start && !$end) {
            return $now >= $start;
        }

        if (!$start && $end) {
            return $now <= $end;
        }

        return false;
    }

    /**
     * Вычисляет цену товара с учетом скидки
     * @return float Цена со скидкой или обычная цена
     */
    public function getDiscountedPrice()
    {
        if ($this->hasValidDiscount()) {
            return $this->price * (1 - $this->discount_percent / 100);
        }
        return $this->price;
    }

    /**
     * Получает записи избранных товаров
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(Favorite::class, ['product_id' => 'id']);
    }

    /**
     * Проверяет, добавлен ли товар в избранное у пользователя
     * @param int $userId ID пользователя
     * @return boolean true если товар в избранном
     */
    public function isFavorite($userId)
    {
        return Favorite::find()
            ->where(['user_id' => $userId, 'product_id' => $this->id])
            ->exists();
    }
}
