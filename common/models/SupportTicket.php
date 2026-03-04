<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

class SupportTicket extends ActiveRecord
{
    const STATUS_OPEN = 'open';
    const STATUS_REPLIED = 'replied';
    const STATUS_CLOSED = 'closed';

    public static function tableName()
    {
        return '{{%support_ticket}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class, // ✅ QO'SHILDI
        ];
    }

    public function rules()
    {
        return [
            [['user_id', 'subject', 'message'], 'required'],
            [['user_id', 'admin_replied_at'], 'integer'], // ✅ admin_replied_at qo'shildi
            [['subject'], 'string', 'max' => 255],
            [['message', 'admin_reply'], 'string'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'in', 'range' => [self::STATUS_OPEN, self::STATUS_REPLIED, self::STATUS_CLOSED]], // ✅ validation
        ];
    }

    public static function getUserTickets($userId)
    {
        return self::find()
            ->where(['user_id' => $userId])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    public static function getPendingTickets()
    {
        return self::find()
            ->where(['status' => self::STATUS_OPEN])
            ->orderBy(['id' => SORT_DESC])
            ->all();
    }

    // ✅ YANGI METHOD
    public static function getUnreadCount($userId)
    {
        return self::find()
            ->where(['user_id' => $userId, 'status' => self::STATUS_REPLIED])
            ->count();
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}