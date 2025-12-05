<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class SupportTicket extends ActiveRecord
{
    const STATUS_OPEN = 'open';
    const STATUS_REPLIED = 'replied';
    const STATUS_CLOSED = 'closed';

    public static function tableName()
    {
        return '{{%support_ticket}}';
    }

    public function rules()
    {
        return [
            [['user_id', 'subject', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['subject'], 'string', 'max' => 255],
            [['message', 'admin_reply'], 'string'],
            [['status'], 'string'],
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

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
