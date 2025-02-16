<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class Article extends ActiveRecord
{
    public static function tableName()
    {
        return 'articles';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['image'], 'file', 'extensions' => 'png, jpg, jpeg', 'skipOnEmpty' => true],
            [['user_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'content' => 'Содержание',
            'image' => 'Изображение',
            'user_id' => 'Автор',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function upload()
    {
        if ($this->image instanceof \yii\web\UploadedFile) {
            $path = Yii::getAlias('@webroot/uploads/') . uniqid() . '.' . $this->image->extension;
            if ($this->image->saveAs($path)) {
                // Сохраняем старое изображение для возможного удаления
                $oldImage = $this->getOldAttribute('image');
                
                // Обновляем путь к новому изображению
                $this->image = str_replace(Yii::getAlias('@webroot/'), '', $path);
                
                // Если сохранение прошло успешно и было старое изображение, удаляем его
                if ($this->save(false) && $oldImage) {
                    $oldImagePath = Yii::getAlias('@webroot/') . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                return true;
            }
            return false;
        }
        return true;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
