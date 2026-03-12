<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Settings model
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string|null $description
 */
class Setting extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%settings}}';
    }

    public function rules()
    {
        return [
            [['key'], 'required'],
            [['value', 'description'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * Kalit (key) orqali qiymatni tezkor olish uchun yordamchi funksiya
     */
    public static function getValue($key, $default = null)
    {
        $setting = self::findOne(['key' => $key]);
        return $setting ? $setting->value : $default;
    }
}