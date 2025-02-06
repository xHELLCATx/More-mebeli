<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Модель SEO-параметров страницы
 * Управляет мета-данными и SEO-настройками страниц сайта
 *
 * @property int $id ID записи
 * @property string $page_url URL страницы
 * @property string $page_title Заголовок страницы
 * @property string $meta_title Meta Title страницы
 * @property string $meta_description Meta Description страницы
 * @property string $meta_keywords Meta Keywords страницы
 * @property string $created_at Дата создания
 * @property string $updated_at Дата обновления
 */
class PageSeo extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'page_seo';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['page_url', 'page_title'], 'required'],
            // Текстовое поле для мета-описания
            [['meta_description'], 'string'],
            // Ограничение длины строковых полей
            [['page_url', 'page_title', 'meta_title', 'meta_keywords'], 'string', 'max' => 255],
            // Уникальный URL страницы
            [['page_url'], 'unique'],
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
            'page_url' => 'URL страницы',
            'page_title' => 'Заголовок страницы',
            'meta_title' => 'Meta Title',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }
}
