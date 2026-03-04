<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class Schedule extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%schedule}}';
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
            [['group_id', 'teacher_id', 'day_of_week', 'start_time', 'end_time'], 'required'],
            [['group_id', 'teacher_id', 'day_of_week'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['room'], 'string', 'max' => 100],
            ['day_of_week', 'in', 'range' => [1, 2, 3, 4, 5, 6, 7]],
            ['end_time', 'compare', 'compareAttribute' => 'start_time', 'operator' => '>', 'message' => 'End time must be after start time.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'group_id' => 'Group',
            'teacher_id' => 'Teacher',
            'day_of_week' => 'Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'room' => 'Room/Location',
        ];
    }

    public function getGroup()
    {
        return $this->hasOne(Group::class, ['id' => 'group_id']);
    }

    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    public static function getDayNames()
    {
        return [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];
    }

    public function getDayName()
    {
        $days = self::getDayNames();
        return $days[$this->day_of_week] ?? '';
    }

    public function getDuration()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        $diff = ($end - $start) / 3600; // hours
        return round($diff, 1);
    }
}