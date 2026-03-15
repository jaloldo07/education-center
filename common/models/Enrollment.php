<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Enrollment extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_WAITING_PAYMENT = 'waiting_payment';

    public static function tableName()
    {
        return '{{%enrollment}}';
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
            // group_id o'rniga course_id yozildi
            [['student_id', 'course_id', 'enrolled_on'], 'required'],
            [['student_id', 'course_id'], 'integer'],
            [['enrolled_on'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_WAITING_PAYMENT]],
            
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            // course_id Course jadvalidan borligini tekshiramiz
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student',
            'course_id' => 'Course', // Group o'rniga Course
            'enrolled_on' => 'Enrolled On',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id'])->inverseOf('enrollments');
    }

    // getGroup() funksiyasi butunlay o'chirildi va getCourse to'g'ridan-to'g'ri ulandi
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']); 
    }
}