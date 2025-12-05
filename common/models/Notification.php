<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Notification extends ActiveRecord
{
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';

    public static function tableName()
    {
        return '{{%notification}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'title', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['message'], 'string'],
            [['title', 'link'], 'string', 'max' => 255],
            [['type'], 'string', 'max' => 20],
            [['is_read'], 'boolean'],
            [['created_at'], 'integer'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function notify($userId, $title, $message, $type = self::TYPE_INFO, $link = null)
    {
        $notification = new self();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->message = $message;
        $notification->type = $type;
        $notification->link = $link;
        $notification->is_read = false;
        return $notification->save();
    }

    public function markAsRead()
    {
        $this->is_read = true;
        return $this->save(false);
    }
}