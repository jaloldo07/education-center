<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "test_answer".
 *
 * @property int $id
 * @property int $attempt_id
 * @property int $question_id
 * @property string|null $answer
 * @property bool|null $is_correct
 * @property int|null $points_awarded
 * @property int $answered_at
 * @property int $created_at
 * @property int $updated_at
 *
 * @property TestAttempt $attempt
 * @property TestQuestion $question
 */
class TestAnswer extends ActiveRecord
{
    public $answerArray = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_answer}}';
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
            [['attempt_id', 'question_id', 'answered_at'], 'required'],
            [['attempt_id', 'question_id', 'points_awarded', 'answered_at'], 'integer'],
            [['answer'], 'string'],
            [['is_correct'], 'boolean'],
            [['attempt_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestAttempt::class, 'targetAttribute' => ['attempt_id' => 'id']],
            [['question_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestQuestion::class, 'targetAttribute' => ['question_id' => 'id']],
            [['answerArray'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'attempt_id' => 'Attempt',
            'question_id' => 'Question',
            'answer' => 'Answer',
            'is_correct' => 'Is Correct',
            'points_awarded' => 'Points Awarded',
            'answered_at' => 'Answered At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->answerArray = $this->answer ? Json::decode($this->answer) : [];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if (is_array($this->answerArray)) {
                $this->answer = Json::encode($this->answerArray);
            }
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Attempt]].
     */
    public function getAttempt()
    {
        return $this->hasOne(TestAttempt::class, ['id' => 'attempt_id']);
    }

    /**
     * Gets query for [[Question]].
     */
    public function getQuestion()
    {
        return $this->hasOne(TestQuestion::class, ['id' => 'question_id']);
    }

    /**
     * Grade the answer
     */
    public function grade()
    {
        $question = $this->question;

        // Pass answer in correct format
        if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE) {
            // For single choice, pass the first element, not array
            $studentAnswer = isset($this->answerArray[0]) ? $this->answerArray[0] : null;
        } elseif ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE) {
            // For multiple choice, pass the full array
            $studentAnswer = $this->answerArray;
        } else {
            // For text, pass the string
            $studentAnswer = $this->answerArray[0] ?? '';
        }

        $this->is_correct = $question->checkAnswer($studentAnswer);
        $this->points_awarded = $this->is_correct ? $question->points : 0;

        return $this->save(false, ['is_correct', 'points_awarded']);
    }
}
