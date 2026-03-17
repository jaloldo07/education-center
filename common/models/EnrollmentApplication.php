<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class EnrollmentApplication extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public static function tableName()
    {
        return '{{%enrollment_application}}';
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
            // 🔥 group_id olib tashlandi
            [['student_id', 'course_id'], 'required'],
            [['student_id', 'course_id', 'reviewed_by', 'reviewed_at'], 'integer'],
            [['message', 'admin_comment'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_PENDING, self::STATUS_APPROVED, self::STATUS_REJECTED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student',
            'course_id' => 'Course',
            // 🔥 group_id olib tashlandi
            'message' => 'Message',
            'status' => 'Status',
            'admin_comment' => 'Admin Comment',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    // 🔥 getGroup() funksiyasi butunlay o'chirib tashlandi!

    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
        ];
    }

    public static function getStatusBadgeClass($status)
    {
        return [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
        ][$status] ?? 'secondary';
    }
}