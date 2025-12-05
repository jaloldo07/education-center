<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use common\models\ChatMessage;
use common\models\User;

class ChatApiController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    /**
     * Get conversation list
     */
    public function actionGetContacts()
    {
        $currentUser = Yii::$app->user->identity;
        $contacts = [];

        if ($currentUser->teacher) {
            $teacher = $currentUser->teacher;

            // Get students enrolled in teacher's groups
            $students = \common\models\Student::find()
                ->joinWith(['enrollments' => function ($query) use ($teacher) {
                    $query->joinWith('group')
                        ->where(['group.teacher_id' => $teacher->id]);
                }])
                ->groupBy('student.id')
                ->all();

            foreach ($students as $student) {
                $unread = ChatMessage::find()
                    ->where(['sender_id' => $student->user_id, 'receiver_id' => $currentUser->id, 'is_read' => 0])
                    ->count();

                $contacts[] = [
                    'id' => $student->user_id,
                    'name' => $student->full_name,
                    'role' => 'Student',
                    'unread' => $unread,
                ];
            }
        } elseif ($currentUser->student) {
            $student = $currentUser->student;

            // Get teachers from groups student is enrolled in
            $teachers = \common\models\Teacher::find()
                ->joinWith(['groups' => function ($query) use ($student) {
                    $query->joinWith('enrollments')
                        ->where(['enrollment.student_id' => $student->id]);
                }])
                ->groupBy('teacher.id')
                ->all();

            foreach ($teachers as $teacher) {
                $unread = ChatMessage::find()
                    ->where(['sender_id' => $teacher->user_id, 'receiver_id' => $currentUser->id, 'is_read' => 0])
                    ->count();

                $contacts[] = [
                    'id' => $teacher->user_id,
                    'name' => $teacher->full_name,
                    'role' => 'Teacher',
                    'unread' => $unread,
                ];
            }
        }

        return ['success' => true, 'contacts' => $contacts];
    }

    /**
     * Get messages with specific user
     */
    public function actionGetMessages($userId)
    {
        $currentUserId = Yii::$app->user->id;
        $messages = ChatMessage::getConversation($currentUserId, $userId);

        // Mark as read
        ChatMessage::markAsRead($userId, $currentUserId);

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id' => $msg->id,
                'message' => $msg->message,
                'is_mine' => $msg->sender_id == $currentUserId,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg->created_at),
            ];
        }

        return ['success' => true, 'messages' => $result];
    }

    /**
     * Send message
     */
    public function actionSend()
    {
        $receiverId = Yii::$app->request->post('receiver_id');
        $message = Yii::$app->request->post('message');

        if (empty($receiverId) || empty($message)) {
            return ['success' => false, 'error' => 'Invalid data'];
        }

        $chat = new ChatMessage();
        $chat->sender_id = Yii::$app->user->id;
        $chat->receiver_id = $receiverId;
        $chat->message = $message;

        if ($chat->save()) {
            return [
                'success' => true,
                'message' => [
                    'id' => $chat->id,
                    'message' => $chat->message,
                    'is_mine' => true,
                    'created_at' => 'Just now',
                ]
            ];
        }

        return ['success' => false, 'error' => 'Failed to send'];
    }

    /**
     * Get new messages (for polling)
     */
    public function actionGetNew($userId, $lastId = 0)
    {
        $currentUserId = Yii::$app->user->id;

        $messages = ChatMessage::find()
            ->where([
                'or',
                ['and', ['sender_id' => $userId], ['receiver_id' => $currentUserId]],
                ['and', ['sender_id' => $currentUserId], ['receiver_id' => $userId]]
            ])
            ->andWhere(['>', 'id', $lastId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        // Mark as read
        ChatMessage::markAsRead($userId, $currentUserId);

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id' => $msg->id,
                'message' => $msg->message,
                'is_mine' => $msg->sender_id == $currentUserId,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg->created_at),
            ];
        }

        return ['success' => true, 'messages' => $result];
    }


    public function actionGetUnreadCount()
    {
        $currentUserId = Yii::$app->user->id;

        $count = ChatMessage::find()
            ->where(['receiver_id' => $currentUserId, 'is_read' => 0])
            ->count();

        return ['success' => true, 'count' => $count];
    }
}
