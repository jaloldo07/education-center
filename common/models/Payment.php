<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "payment".
 *
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property float $amount
 * @property string $payment_date
 * @property string $payment_type
 * @property string $payment_method
 * @property string|null $transaction_id
 * @property string|null $note
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Student $student
 * @property Course $course
 */
class Payment extends ActiveRecord
{
    const TYPE_MONTHLY = 'monthly';
    const TYPE_YEARLY = 'yearly';
    const TYPE_FULL = 'full';

    const STATUS_PENDING = 0;
    const STATUS_PAID = 1;
    const STATUS_FAILED = 2;

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
            [['student_id', 'course_id', 'amount', 'payment_method', 'payment_type'], 'required'],
            [['student_id', 'course_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['amount'], 'number', 'min' => 1000],
            
            // payment_date ni avtomatik qo'yamiz, lekin qoida sifatida safe turishi kerak
            [['payment_date'], 'safe'], 
            
            [['note'], 'string'],
            [['transaction_id', 'receipt_file'], 'string', 'max' => 255],
            
            [['payment_type'], 'string', 'max' => 20],
            [['payment_type'], 'in', 'range' => [self::TYPE_MONTHLY, self::TYPE_YEARLY, self::TYPE_FULL]],

            [['payment_method'], 'string', 'max' => 50],
            // online_card borligi frontend to'lovlari uchun muhim
            [['payment_method'], 'in', 'range' => ['cash', 'card', 'click', 'payme', 'bank_transfer', 'online_card']],

            [['student_id'], 'exist', 'skipOnError' => true, 'targetClass' => Student::class, 'targetAttribute' => ['student_id' => 'id']],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            
            // Maxsus Validatsiyalar
            ['student_id', 'validateEnrollment'],
            ['amount', 'validateAmount'],
        ];
    }

    /**
     * Saqlashdan oldin sanani avtomatik qo'yish
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Agar sana kiritilmagan bo'lsa, bugungi sanani qo'yamiz
            if (empty($this->payment_date)) {
                $this->payment_date = date('Y-m-d');
            }
            // Agar online to'lov bo'lsa, statusni avtomatik PAID qilish (yoki PENDING)
            if ($this->payment_method === 'online_card' && $this->isNewRecord) {
                // Hozircha 1 (To'landi) qilamiz, real payme ulanganda 0 bo'ladi
                $this->status = self::STATUS_PAID; 
            }
            return true;
        }
        return false;
    }

    /**
     * Talaba rostan ham tanlangan kursda o'qiydimi?
     */
    /**
     * Talaba rostan ham tanlangan kursda o'qiydimi?
     */
    public function validateEnrollment($attribute, $params)
    {
        // Guruhsiz, to'g'ridan-to'g'ri course_id orqali tekshiramiz
        $hasEnrollment = Enrollment::find()
            ->where([
                'student_id' => $this->student_id,
                'course_id' => $this->course_id
            ])
            ->exists();

        if (!$hasEnrollment) {
            $this->addError($attribute, Yii::t('app', 'This student is not enrolled in the selected course.'));
            $this->addError('course_id', Yii::t('app', 'Student does not study in this course.'));
        }
    }

    /**
     * To'lov summasi kurs narxiga mosligini tekshirish
     */
    public function validateAmount($attribute, $params)
    {
        if (!$this->course) return;

        // Kurs narxi
        $price = $this->course->price;

        // Agar Oylik to'lov bo'lsa -> Narx kurs narxidan kam bo'lmasligi kerak
        if ($this->payment_type === self::TYPE_MONTHLY) {
            if ($this->amount < $price) {
                $this->addError($attribute, Yii::t('app', 'Amount is less than the course monthly price: {price}', [
                    'price' => number_format($price, 0, '.', ' ') . ' UZS'
                ]));
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'student_id' => Yii::t('app', 'Student'),
            'course_id' => Yii::t('app', 'Course'),
            'amount' => Yii::t('app', 'Amount'),
            'status' => Yii::t('app', 'Status'),
            'payment_method' => Yii::t('app', 'Payment Method'),
            'transaction_id' => Yii::t('app', 'Transaction ID'),
            'payment_date' => Yii::t('app', 'Payment Date'),
            'payment_type' => Yii::t('app', 'Payment Period'),
            'receipt_file' => Yii::t('app', 'Receipt / Chek'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
    
    public function getStudent() { return $this->hasOne(Student::class, ['id' => 'student_id']); }
    public function getCourse() { return $this->hasOne(Course::class, ['id' => 'course_id']); }
}