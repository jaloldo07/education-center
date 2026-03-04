<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Teacher model
 * * @property int $id
 * @property int $user_id
 * @property string $full_name
 * @property string $subject
 * @property int $experience_years
 * @property string $phone
 * @property string $email
 * @property string $bio
 * @property float $rating
 * @property int $created_at
 * @property int $updated_at
 */
class Teacher extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            // 🔥 1. MUHIM: experience_years va phone majburiy qilindi.
            // Bu "Column cannot be null" xatosini yo'qotadi.
            [['full_name', 'subject', 'email', 'phone', 'experience_years'], 'required'],

            // 2. Raqamli ma'lumotlar
            [['experience_years', 'user_id'], 'integer'],
            [['rating'], 'number', 'min' => 0, 'max' => 5],

            // 3. Matnli ma'lumotlar
            [['bio'], 'string'],
            [['full_name', 'subject', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],

            // 4. Email tekshiruvlari
            [['email'], 'email'],
            [['email'], 'unique'], // Email bazada qaytarilmasligi kerak

            // 5. Default qiymatlar (Xatolik oldini olish uchun)
            ['rating', 'default', 'value' => 0], // Agar rating kiritilmasa, 0 deb oladi
            ['experience_years', 'default', 'value' => 0], // Ehtiyot shart, garchi required bo'lsa ham

            // 6. User bog'lanishi (Data Integrity)
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Foydalanuvchi (User)'),
            'full_name' => Yii::t('app', 'F.I.Sh'),
            'subject' => Yii::t('app', 'Fan'),
            'experience_years' => Yii::t('app', 'Tajriba (yil)'),
            'phone' => Yii::t('app', 'Telefon'),
            'email' => Yii::t('app', 'Email'),
            'bio' => Yii::t('app', 'Biografiya'),
            'rating' => Yii::t('app', 'Reyting'),
            'created_at' => Yii::t('app', 'Yaratildi'),
            'updated_at' => Yii::t('app', 'Yangilandi'),
        ];
    }

    public function getCourses()
    {
        return $this->hasMany(Course::class, ['teacher_id' => 'id'])->inverseOf('teacher');
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['teacher_id' => 'id'])->inverseOf('teacher');
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}