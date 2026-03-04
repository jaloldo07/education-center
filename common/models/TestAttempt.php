<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "test_attempt".
 *
 * @property int $id
 * @property int $test_id
 * @property int $student_id
 * @property string|null $face_photo
 * @property int $started_at
 * @property int|null $finished_at
 * @property float|null $score
 * @property int|null $points_earned
 * @property int|null $total_points
 * @property string $status
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Test $test
 * @property Student $student
 * @property TestAnswer[] $answers
 */
class TestAttempt extends ActiveRecord
{
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ABANDONED = 'abandoned';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_attempt}}';
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
            [['test_id', 'student_id', 'started_at'], 'required'],
            [['test_id', 'student_id', 'started_at', 'finished_at', 'points_earned', 'total_points'], 'integer'],
            [['score'], 'number'],
            [['face_photo', 'user_agent'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_IN_PROGRESS, self::STATUS_COMPLETED, self::STATUS_ABANDONED]],
            [['ip_address'], 'string', 'max' => 45],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_id' => 'Test',
            'student_id' => 'Student',
            'face_photo' => 'Face Photo',
            'started_at' => 'Started At',
            'finished_at' => 'Finished At',
            'score' => 'Score (%)',
            'points_earned' => 'Points Earned',
            'total_points' => 'Total Points',
            'status' => 'Status',
            'ip_address' => 'IP Address',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Test]].
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * Gets query for [[Student]].
     */
    public function getStudent()
    {
        return $this->hasOne(Student::class, ['id' => 'student_id']);
    }

    /**
     * Gets query for [[Answers]].
     */
    public function getAnswers()
    {
        return $this->hasMany(TestAnswer::class, ['attempt_id' => 'id']);
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_ABANDONED => 'Abandoned',
        ];
    }

    /**
     * Get status badge class
     */
    public static function getStatusBadgeClass($status)
    {
        return [
            self::STATUS_IN_PROGRESS => 'warning',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_ABANDONED => 'danger',
        ][$status] ?? 'secondary';
    }

    /**
     * Calculate and save final score
     */
    public function calculateScore()
    {
        $test = $this->test;
        $totalPoints = $test->getTotalPoints();
        
        $earnedPoints = 0;
        foreach ($this->answers as $answer) {
            if ($answer->is_correct) {
                $earnedPoints += $answer->points_awarded;
            }
        }

        $this->points_earned = $earnedPoints;
        $this->total_points = $totalPoints;
        $this->score = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100, 2) : 0;
        
        return $this->save(false, ['points_earned', 'total_points', 'score']);
    }

    /**
     * Complete the attempt
     */
    public function complete()
    {
        $this->status = self::STATUS_COMPLETED;
        $this->finished_at = time();
        
        if ($this->save(false, ['status', 'finished_at'])) {
            $this->calculateScore();
            return true;
        }
        
        return false;
    }

    /**
     * Get duration in minutes
     */
    public function getDuration()
    {
        if (!$this->finished_at) {
            return null;
        }
        
        return round(($this->finished_at - $this->started_at) / 60, 1);
    }

    /**
     * Check if passed
     */
    public function isPassed()
    {
        return $this->score >= $this->test->passing_score;
    }
}