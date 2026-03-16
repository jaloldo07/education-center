<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Student extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student}}';
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
            // birth_date ham majburiy qilindi, aks holda yoshini tekshira olmaymiz
            [['full_name', 'email', 'enrolled_date', 'birth_date'], 'required'], 
            
            [['user_id'], 'integer'],
            [['enrolled_date'], 'safe'],
            [['address'], 'string'],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
            
            // 🔥 YOSH CHEGARASI VA SANA TEKSHIRUVI
            ['birth_date', 'date', 'format' => 'php:Y-m-d', 
                // Maksimum sana: Bugundan 7 yil oldingi sana.
                // Masalan, bugun 2026 yil bo'lsa, 2019 dan keyingi (katta) sanalarni qabul qilmaydi.
                'max' => date('Y-m-d', strtotime('-7 years')), 
                'tooBig' => Yii::t('app', 'Talaba kamida 7 yosh bo\'lishi kerak.'),
                'message' => Yii::t('app', 'Sana formati noto\'g\'ri (YYYY-MM-DD).')
            ],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name' => Yii::t('app', 'Full Name'),
            'birth_date' => Yii::t('app', 'Birth Date'),
            'phone' => Yii::t('app', 'Phone'),
            'email' => Yii::t('app', 'Email'),
            'address' => Yii::t('app', 'Address'),
            'enrolled_date' => Yii::t('app', 'Enrolled Date'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getEnrollments()
    {
        return $this->hasMany(Enrollment::class, ['student_id' => 'id'])->inverseOf('student');
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['student_id' => 'id'])->inverseOf('student');
    }


    public function getAttendances()
    {
        return $this->hasMany(Attendance::class, ['student_id' => 'id'])->inverseOf('student');
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}