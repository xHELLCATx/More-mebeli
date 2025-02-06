<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель настроек системы
 * Управляет хранением и получением настроек в формате ключ-значение
 *
 * @property int $id ID настройки
 * @property string $key Ключ настройки
 * @property string $value Значение настройки
 * @property string $description Описание настройки
 */
class Settings extends ActiveRecord
{
    /**
     * Возвращает имя таблицы в базе данных
     * @return string Имя таблицы
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * Правила валидации полей модели
     * @return array Массив правил валидации
     */
    public function rules()
    {
        return [
            // Обязательные поля
            [['key'], 'required'],
            // Ограничение длины ключа
            [['key'], 'string', 'max' => 255],
            // Текстовые поля
            [['value', 'description'], 'string'],
            // Уникальность ключа
            [['key'], 'unique'],
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
            'key' => 'Ключ',
            'value' => 'Значение',
            'description' => 'Описание',
        ];
    }

    /**
     * Получает значение настройки по ключу
     * 
     * @param string $key Ключ настройки
     * @param mixed $default Значение по умолчанию, если настройка не найдена
     * @return mixed Значение настройки или значение по умолчанию
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::findOne(['key' => $key]);
        return $setting ? $setting->value : $default;
    }

    /**
     * Устанавливает значение настройки
     * Если настройка с указанным ключом не существует, создает новую
     * 
     * @param string $key Ключ настройки
     * @param mixed $value Значение настройки
     * @return boolean Результат сохранения
     */
    public static function setValue($key, $value)
    {
        $setting = self::findOne(['key' => $key]);
        if (!$setting) {
            $setting = new self();
            $setting->key = $key;
        }
        $setting->value = $value;
        return $setting->save();
    }
}
