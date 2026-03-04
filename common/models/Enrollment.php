<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Enrollment extends ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_WAITING_PAYMENT = 'waiting_payment'; // 🔥 Yangi status

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
            [['student_id', 'group_id', 'enrolled_on'], 'required'],
            [['student_id', 'group_id'], 'integer'],
            [['enrolled_on'], 'safe'],
            [['status'], 'string', 'max' => 20],
            // 🔥 Yangi status range ichiga qo'shildi
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_COMPLETED, self::STATUS_WAITING_PAYMENT]],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student',
            'group_id' => 'Group',
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

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id'])->inverseOf('enrollments');
    }
    
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id'])
            ->via('group'); 
    }
}