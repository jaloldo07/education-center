<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Student extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%student}}';
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
            [['full_name', 'email', 'enrolled_date'], 'required'], // user_id o'chirildi
            [['user_id'], 'integer'],
            [['birth_date', 'enrolled_date'], 'safe'],
            [['address'], 'string'],
            [['full_name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'full_name' => 'Full Name',
            'birth_date' => 'Birth Date',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'enrolled_date' => 'Enrolled Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    public function getEnrollments()
    {
        return $this->hasMany(Enrollment::class, ['student_id' => 'id'])->inverseOf('student');
        // return $this->hasMany(Enrollment::class, ['student_id' => 'id']);
    }

    public function getPayments()
    {
        return $this->hasMany(Payment::class, ['student_id' => 'id'])->inverseOf('student');
    }

    public function getGroups()
    {
        return $this->hasMany(Group::class, ['id' => 'group_id'])
            ->viaTable('{{%enrollment}}', ['student_id' => 'id']);
    }

    public function getAttendances()
    {
        return $this->hasMany(Attendance::class, ['student_id' => 'id'])->inverseOf('student');
    }
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
