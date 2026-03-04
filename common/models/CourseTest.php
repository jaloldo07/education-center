<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class CourseTest extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'course_test';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function rules()
    {
        return [
            [['course_id', 'test_id'], 'required'],
            [['course_id', 'test_id', 'order_number'], 'integer'],
            [['is_final_test'], 'boolean'],
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }
}