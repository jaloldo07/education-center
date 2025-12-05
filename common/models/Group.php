<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Group extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%group}}';
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
            [['name', 'course_id', 'teacher_id'], 'required'],
            [['course_id', 'teacher_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['course_id'], 'exist', 'skipOnError' => true, 'targetClass' => Course::class, 'targetAttribute' => ['course_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Group Name',
            'course_id' => 'Course',
            'teacher_id' => 'Teacher',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getCourse()
    {
        return $this->hasOne(Course::class, ['id' => 'course_id'])->inverseOf('groups');
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id'])->inverseOf('groups');
    }

    public function getEnrollments()
    {
        return $this->hasMany(Enrollment::class, ['group_id' => 'id'])->inverseOf('group');
    }

    public function getStudents()
    {
        return $this->hasMany(Student::class, ['id' => 'student_id'])
            ->viaTable('{{%enrollment}}', ['group_id' => 'id']);
    }

    public function getAttendances()
    {
        return $this->hasMany(Attendance::class, ['group_id' => 'id'])->inverseOf('group');
    }
}
