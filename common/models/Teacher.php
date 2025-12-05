<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
            [['full_name', 'subject', 'email'], 'required'],
            [['experience_years'], 'integer', 'min' => 0],
            [['bio'], 'string'],
            [['rating'], 'number', 'min' => 0, 'max' => 5],
            [['full_name', 'subject', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'subject' => 'Subject',
            'experience_years' => 'Experience (Years)',
            'phone' => 'Phone',
            'email' => 'Email',
            'bio' => 'Biography',
            'rating' => 'Rating',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
