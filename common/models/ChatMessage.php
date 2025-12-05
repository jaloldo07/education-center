<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ChatMessage extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%chat_message}}';
    }

    public function rules()
    {
        return [
            [['sender_id', 'receiver_id', 'message'], 'required'],
            [['sender_id', 'receiver_id'], 'integer'],
            [['message'], 'string'],
            [['is_read'], 'boolean'],
        ];
    }

    public static function getConversation($user1, $user2, $limit = 30)
    {
        return self::find()
            ->where([
                'or',
                ['and', ['sender_id' => $user1], ['receiver_id' => $user2]],
                ['and', ['sender_id' => $user2], ['receiver_id' => $user1]]
            ])
            ->orderBy(['id' => SORT_ASC])
            ->limit($limit)
            ->all();
    }

    public static function markAsRead($senderId, $receiverId)
    {
        return self::updateAll(
            ['is_read' => 1],
            ['sender_id' => $senderId, 'receiver_id' => $receiverId, 'is_read' => 0]
        );
    }

    public static function getUnreadCount($userId)
    {
        return self::find()
            ->where(['receiver_id' => $userId, 'is_read' => 0])
            ->count();
    }

    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    public function getReceiver()
    {
        return $this->hasOne(User::class, ['id' => 'receiver_id']);
    }
}
