<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;

/**
 * This is the model class for table "test_question".
 *
 * @property int $id
 * @property int $test_id
 * @property string $question_text
 * @property string $question_type
 * @property string|null $options
 * @property string|null $correct_answer
 * @property int $points
 * @property int $order
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Test $test
 * @property TestAnswer[] $answers
 */
class TestQuestion extends ActiveRecord
{
    const TYPE_SINGLE_CHOICE = 'single_choice';
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_TEXT = 'text';

    public $optionsArray = [];
    public $correctAnswerArray = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_question}}';
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
            [['test_id', 'question_text'], 'required'],
            [['test_id', 'points', 'order'], 'integer'],
            [['question_text', 'options', 'correct_answer'], 'string'],
            [['question_type'], 'string', 'max' => 20],
            [['question_type'], 'in', 'range' => [self::TYPE_SINGLE_CHOICE, self::TYPE_MULTIPLE_CHOICE, self::TYPE_TEXT]],
            [['points'], 'integer', 'min' => 1],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
            [['optionsArray'], 'safe'],
            [['correctAnswerArray'], 'safe'],
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
            'question_text' => 'Question',
            'question_type' => 'Question Type',
            'options' => 'Options',
            'correct_answer' => 'Correct Answer',
            'points' => 'Points',
            'order' => 'Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'optionsArray' => 'Options',
            'correctAnswerArray' => 'Correct Answer(s)',
        ];
    }

    /**
     * {@inheritdoc}
     * ✅ FIXED: Proper type normalization on load
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // Decode and normalize types
        $this->optionsArray = $this->options ? Json::decode($this->options) : [];
        $this->correctAnswerArray = $this->correct_answer ? Json::decode($this->correct_answer) : [];
        
        // ✅ CRITICAL FIX: Normalize all values to strings for consistent comparison
        // This ensures "0" === "0" and not 0 !== "0"
        $this->correctAnswerArray = array_map('strval', $this->correctAnswerArray);
    }

    /**
     * {@inheritdoc}
     * ✅ FIXED: Proper type normalization before save
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // ✅ Normalize correctAnswerArray to strings before encoding
            if (is_array($this->correctAnswerArray)) {
                // Remove empty values and normalize to strings
                $this->correctAnswerArray = array_values(
                    array_filter(
                        array_map('strval', $this->correctAnswerArray),
                        function($v) {
                            return $v !== '';
                        }
                    )
                );
                $this->correct_answer = Json::encode($this->correctAnswerArray);
            }
            
            if (is_array($this->optionsArray)) {
                $this->options = Json::encode($this->optionsArray);
            }
            
            return true;
        }
        return false;
    }

    /**
     * Gets query for [[Test]].
     */
    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    /**
     * Gets query for [[Answers]].
     */
    public function getAnswers()
    {
        return $this->hasMany(TestAnswer::class, ['question_id' => 'id']);
    }

    /**
     * Get question type options
     */
    public static function getTypeOptions()
    {
        return [
            self::TYPE_SINGLE_CHOICE => 'Single Choice',
            self::TYPE_MULTIPLE_CHOICE => 'Multiple Choice',
            self::TYPE_TEXT => 'Text Answer',
        ];
    }

    /**
     * ✅ FIXED: Proper answer checking with type normalization
     * @param mixed $studentAnswer Student's answer
     * @return bool Whether the answer is correct
     */
    public function checkAnswer($studentAnswer)
    {
        $correctAnswer = $this->correctAnswerArray; // Already normalized to strings in afterFind()

        if ($this->question_type === self::TYPE_SINGLE_CHOICE) {
            // ✅ FIXED: Normalize both to strings for comparison
            // Student answer might be integer or string from form
            $studentAnswerStr = strval($studentAnswer);
            $correctAnswerStr = isset($correctAnswer[0]) ? strval($correctAnswer[0]) : '';
            
            return $studentAnswerStr === $correctAnswerStr;
            
        } elseif ($this->question_type === self::TYPE_MULTIPLE_CHOICE) {
            // ✅ FIXED: Proper array comparison with type normalization
            if (!is_array($studentAnswer)) {
                return false;
            }

            // Normalize all values to strings and remove empty values
            $studentClean = array_values(array_filter(
                array_map('strval', $studentAnswer),
                function ($v) {
                    return $v !== '';
                }
            ));

            $correctClean = array_values(array_filter(
                array_map('strval', $correctAnswer),
                function ($v) {
                    return $v !== '';
                }
            ));

            // Sort both arrays for comparison
            sort($studentClean);
            sort($correctClean);

            // Debug logging (remove in production)
            Yii::info([
                'question_id' => $this->id,
                'student_answer' => $studentClean,
                'correct_answer' => $correctClean,
                'match' => $studentClean === $correctClean
            ], 'test-grading');

            // Strict comparison
            return $studentClean === $correctClean;
            
        } elseif ($this->question_type === self::TYPE_TEXT) {
            // ✅ Case-insensitive text comparison
            $studentText = trim($studentAnswer);
            $correctText = isset($correctAnswer[0]) ? trim($correctAnswer[0]) : '';
            
            return strtolower($studentText) === strtolower($correctText);
        }

        return false;
    }
}
