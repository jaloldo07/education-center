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
    // 🔥 1. TYPE CONSTANTLARINI MOSLASHTIRISH
    // Formada 'text_answer' ishlatilayotgani uchun bu yerda ham shunday bo'lishi kerak.
    const TYPE_SINGLE_CHOICE = 'single_choice';
    const TYPE_MULTIPLE_CHOICE = 'multiple_choice';
    const TYPE_TEXT = 'text_answer'; // Oldin 'text' edi, endi 'text_answer'

    public $optionsArray = [];
    public $correctAnswerArray = [];

    public static function tableName()
    {
        return '{{%test_question}}';
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
            [['test_id', 'question_text', 'question_type'], 'required'],
            [['test_id', 'points', 'order'], 'integer'],
            [['question_text', 'options', 'correct_answer'], 'string'],
            [['question_type'], 'string', 'max' => 20],
            
            // 🔥 2. RANGE TEKSHIRUVI
            // Bu yerda endi self::TYPE_TEXT ('text_answer') ruxsat etilgan bo'ladi.
            [['question_type'], 'in', 'range' => [
                self::TYPE_SINGLE_CHOICE, 
                self::TYPE_MULTIPLE_CHOICE, 
                self::TYPE_TEXT
            ]],
            
            [['points'], 'integer', 'min' => 1],
            [['test_id'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['test_id' => 'id']],
            
            [['optionsArray'], 'safe'],
            [['correctAnswerArray'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'test_id' => Yii::t('app', 'Test'),
            'question_text' => Yii::t('app', 'Question'),
            'question_type' => Yii::t('app', 'Question Type'),
            'options' => Yii::t('app', 'Options'),
            'correct_answer' => Yii::t('app', 'Correct Answer'),
            'points' => Yii::t('app', 'Points'),
            'order' => Yii::t('app', 'Order'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        
        // JSON dan Arrayga o'tkazish
        $this->optionsArray = $this->options ? Json::decode($this->options) : [];
        
        // Correct Answer ba'zan string, ba'zan array bo'lishi mumkin, shuni arrayga keltiramiz
        $decodedAnswer = $this->correct_answer ? Json::decode($this->correct_answer) : [];
        
        if (is_array($decodedAnswer)) {
            $this->correctAnswerArray = array_map('strval', $decodedAnswer);
        } else {
            // Agar string bo'lsa (eski ma'lumotlar uchun), uni arrayga solamiz
            $this->correctAnswerArray = [strval($decodedAnswer)];
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            
            // 1. Agar savol turi 'text_answer' bo'lsa
            if ($this->question_type === self::TYPE_TEXT) {
                $this->options = null; // Variantlar bo'lmaydi
                
                // Javobni array ichida saqlaymiz (masalan: ["javob"])
                if (is_array($this->correctAnswerArray)) {
                     // Agar array kelsa, birinchi elementni olamiz
                     $val = reset($this->correctAnswerArray);
                     $this->correct_answer = Json::encode([trim($val)]);
                } else {
                     // Agar string kelsa
                     $this->correct_answer = Json::encode([trim($this->correctAnswerArray)]);
                }
                
            } else {
                // 2. Agar variantli savol bo'lsa (Single/Multiple)
                
                // To'g'ri javoblarni saqlash
                if (is_array($this->correctAnswerArray)) {
                    // Faqat stringga o'tkazib saqlaymiz (raqamlar ham string bo'lsin)
                    $cleanAnswers = array_map('strval', $this->correctAnswerArray);
                    $this->correct_answer = Json::encode(array_values($cleanAnswers));
                }
                
                // Variantlarni saqlash
                if (is_array($this->optionsArray)) {
                    $this->options = Json::encode(array_values($this->optionsArray));
                }
            }
            
            return true;
        }
        return false;
    }

    public function getTest()
    {
        return $this->hasOne(Test::class, ['id' => 'test_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(TestAnswer::class, ['question_id' => 'id']);
    }

    /**
     * Dropdown uchun variantlar
     */
    public static function getTypeOptions()
    {
        return [
            self::TYPE_SINGLE_CHOICE => Yii::t('app', 'Single Choice'),
            self::TYPE_MULTIPLE_CHOICE => Yii::t('app', 'Multiple Choice'),
            self::TYPE_TEXT => Yii::t('app', 'Text Answer'),
        ];
    }
}