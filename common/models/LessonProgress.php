<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class LessonProgress extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'lesson_progress';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['student_id', 'lesson_id'], 'required'],
            [['student_id', 'lesson_id', 'progress_percentage', 'time_spent', 'video_progress', 'completed_at'], 'integer'],
            [['status'], 'in', 'range' => ['not_started', 'in_progress', 'completed']],
            [['progress_percentage'], 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    public function getLesson()
    {
        return $this->hasOne(Lesson::class, ['id' => 'lesson_id']);
    }
}