<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

class Lesson extends \yii\db\ActiveRecord
{
    public $file;
    
    public static function tableName()
    {
        return 'lesson';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['course_id', 'title', 'content_type'], 'required'],
            [['course_id', 'order_number', 'duration_minutes', 'min_watch_time'], 'integer'],
            [['description', 'content'], 'string'],
            [['content_type'], 'in', 'range' => ['video', 'text', 'pdf', 'image']],
            [['difficulty_level'], 'in', 'range' => ['easy', 'medium', 'hard']],
            [['is_published'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['file_path', 'video_url'], 'string', 'max' => 500],
            ['file', 'file', 'extensions' => 'pdf, jpg, jpeg, png, mp4', 'maxSize' => 50 * 1024 * 1024],
        ];
    }

    // --- O'ZGARTIRILGAN QISM ---
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content_type' => Yii::t('app', 'Content Type'),
            'content' => Yii::t('app', 'Content'),
            'order_number' => Yii::t('app', 'Order'),
            'difficulty_level' => Yii::t('app', 'Difficulty'),
            'duration_minutes' => Yii::t('app', 'Duration'),
            'min_watch_time' => Yii::t('app', 'Minimum Watch Time'),
            'video_url' => Yii::t('app', 'Video URL'),
            'file_path' => Yii::t('app', 'File Path'),
            'file' => Yii::t('app', 'File'),
            'is_published' => Yii::t('app', 'Published'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    // ---------------------------

    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    public function getProgress()
    {
        return $this->hasMany(LessonProgress::class, ['lesson_id' => 'id']);
    }
}