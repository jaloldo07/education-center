<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Payment extends ActiveRecord
{
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';

    public static function tableName()
    {
        return '{{%payment}}';
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
            [['student_id', 'course_id', 'amount', 'payment_date'], 'required'],
            [['student_id', 'course_id'], 'integer'],
            [['amount'], 'number', 'min' => 0],
            [['payment_date'], 'safe'],
            [['note'], 'string'],
            [['payment_type'], 'string', 'max' => 20],
            [['payment_type'], 'in', 'range' => [self::TYPE_MONTHLY, self::TYPE_YEARLY]],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => 'Student',
            'course_id' => 'Course',
            'amount' => 'Amount',
            'payment_date' => 'Payment Date',
            'payment_type' => 'Payment Type',
            'note' => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id'])->inverseOf('payments');
    }

    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id'])->inverseOf('payments');
    }
}