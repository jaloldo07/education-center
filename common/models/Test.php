<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "test".
 *
 * @property int $id
 * @property int $teacher_id
 * @property int $course_id
 * @property int|null $group_id
 * @property string $title
 * @property string|null $description
 * @property int $duration
 * @property int $passing_score
 * @property int $total_questions
 * @property string $status
 * @property string|null $start_date
 * @property string|null $end_date
 * @property bool $require_face_control
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Teacher $teacher
 * @property Course $course
 * @property Group $group
 * @property TestQuestion[] $questions
 * @property TestAttempt[] $attempts
 */
class Test extends ActiveRecord
{
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['teacher_id', 'course_id', 'title', 'duration', 'passing_score'], 'required'],
            [['teacher_id', 'course_id', 'group_id', 'duration', 'passing_score', 'total_questions'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['require_face_control'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_DRAFT, self::STATUS_ACTIVE, self::STATUS_CLOSED]],
            [['passing_score'], 'integer', 'min' => 0, 'max' => 100],
            [['duration'], 'integer', 'min' => 1],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::class, 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'teacher_id' => 'Teacher',
            'course_id' => 'Course',
            'group_id' => 'Group (Optional)',
            'title' => 'Test Title',
            'description' => 'Description',
            'duration' => 'Duration (Minutes)',
            'passing_score' => 'Passing Score (%)',
            'total_questions' => 'Total Questions',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'require_face_control' => 'Require Face Control',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Teacher]].
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    /**
     * Gets query for [[Course]].
     */
    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id']);
    }

    /**
     * Gets query for [[Group]].
     */
    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    /**
     * Gets query for [[Questions]].
     */
    public function getQuestions()
    {
        return $this->hasMany(TestQuestion::class, ['test_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }

    /**
     * Gets query for [[Attempts]].
     */
    public function getAttempts()
    {
        return $this->hasMany(TestAttempt::class, ['test_id' => 'id']);
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    /**
     * Get status badge class
     */
    public static function getStatusBadgeClass($status)
    {
        return [
            self::STATUS_DRAFT => 'secondary',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_CLOSED => 'danger',
        ][$status] ?? 'secondary';
    }

    /**
     * Check if test is available for students
     */
    public function isAvailable()
    {
        if ($this->status !== self::STATUS_ACTIVE) {
            return false;
        }

        $now = time();

        if ($this->start_date && strtotime($this->start_date) > $now) {
            return false;
        }

        if ($this->end_date && strtotime($this->end_date) < $now) {
            return false;
        }

        return true;
    }

    /**
     * Calculate total points
     */
    public function getTotalPoints()
    {
        return (int) $this->getQuestions()->sum('points');
    }

    /**
     * Update total questions count
     */
    public function updateTotalQuestions()
    {
        $this->total_questions = $this->getQuestions()->count();
        return $this->save(false, ['total_questions']);
    }
}