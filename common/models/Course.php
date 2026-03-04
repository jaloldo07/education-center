<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Course extends ActiveRecord
{
    const TYPE_FREE = 'free';
    const TYPE_PREMIUM = 'premium';

    public static function tableName()
    {
        return '{{%course}}';
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
            [['name', 'duration', 'price', 'teacher_id', 'type'], 'required'],
            [['duration', 'teacher_id'], 'integer'],
            [['price'], 'number', 'min' => 0],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['type'], 'in', 'range' => [self::TYPE_FREE, self::TYPE_PREMIUM]],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Course Name',
            'description' => 'Description',
            'duration' => 'Duration (Months)',
            'price' => 'Price',
            'teacher_id' => 'Teacher',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'type' => 'Enrollment Type',
        ];
    }

    public static function getTypeOptions()
    {
        return [
            self::TYPE_FREE => 'Free / Auto-Enroll (Direct Access)',
            self::TYPE_PREMIUM => 'Premium / Application (Admin Approval)',
        ];
    }

    public function isFree()
    {
        return $this->type === self::TYPE_FREE;
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id'])->inverseOf('courses');
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['course_id' => 'id'])->inverseOf('course');
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['course_id' => 'id'])->inverseOf('course');
    }
}