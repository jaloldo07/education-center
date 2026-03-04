<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\AccessControl;
use common\models\ChatMessage;
use common\models\User;

class MessageController extends Controller
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
                'is_group' => (bool)$msg->is_group_message,
                'group_id' => $msg->group_id,
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
                'is_group' => (bool)$msg->is_group_message,
                'group_id' => $msg->group_id,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg->created_at),
            ];
        }

        return ['success' => true, 'messages' => $result];
    }

    // Nomini o'zgartirdik: GetUnreadCount -> Count
    public function actionCount()
    {
        $currentUserId = Yii::$app->user->id;

        $count = ChatMessage::find()
            ->where(['receiver_id' => $currentUserId, 'is_read' => 0])
            ->count();

        return ['success' => true, 'count' => $count];
    }

    /**
     * Get teacher's groups
     */
    public function actionGetGroups()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $teacher = $currentUser->teacher;

        $groups = \common\models\Group::find()
            ->where(['teacher_id' => $teacher->id])
            ->all();

        $result = [];
        foreach ($groups as $group) {
            $studentCount = \common\models\Enrollment::find()
                ->where(['group_id' => $group->id, 'status' => 'active'])
                ->count();

            // FIXED: Alias and Table name
            $lastMessage = \common\models\ChatMessage::find()
                ->select(['chat_message.created_at'])
                ->alias('chat_message')
                ->where(['chat_message.sender_id' => $currentUser->id])
                ->innerJoin('user', 'user.id = chat_message.receiver_id')
                ->innerJoin('student', 'student.user_id = user.id')
                ->innerJoin('enrollment', 'enrollment.student_id = student.id')
                ->andWhere(['enrollment.group_id' => $group->id])
                ->orderBy(['chat_message.created_at' => SORT_DESC])
                ->one();

            $result[] = [
                'id' => $group->id,
                'name' => $group->name,
                'student_count' => $studentCount,
                'last_message' => $lastMessage ? Yii::$app->formatter->asRelativeTime($lastMessage->created_at) : 'No messages',
            ];
        }

        return $this->asJson(['success' => true, 'groups' => $result]);
    }

    /**
     * Send message to all students in a group (broadcast)
     */
    public function actionSendGroupMessage()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $groupId = Yii::$app->request->post('group_id');
        $message = Yii::$app->request->post('message');

        if (empty($groupId) || empty($message)) {
            return $this->asJson(['success' => false, 'error' => 'Invalid data']);
        }

        $group = \common\models\Group::findOne([
            'id' => $groupId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$group) {
            return $this->asJson(['success' => false, 'error' => 'Group not found']);
        }

        $students = \common\models\Student::find()
            ->joinWith('enrollments')
            ->where(['enrollment.group_id' => $groupId, 'enrollment.status' => 'active'])
            ->all();

        if (empty($students)) {
            return $this->asJson(['success' => false, 'error' => 'No students in group']);
        }

        $sentCount = 0;
        foreach ($students as $student) {
            $chat = new ChatMessage();
            $chat->sender_id = $currentUser->id;
            $chat->receiver_id = $student->user_id;
            $chat->message = $message;
            $chat->is_group_message = 1;
            $chat->group_id = $groupId;

            if ($chat->save()) {
                $sentCount++;
            }
        }

        return $this->asJson([
            'success' => true,
            'sent_count' => $sentCount,
            'total_students' => count($students),
            'message' => [
                'message' => $message,
                'created_at' => 'Just now',
            ]
        ]);
    }

    /**
     * Get group history
     */
    public function actionGetGroupMessages($groupId)
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $group = \common\models\Group::findOne([
            'id' => $groupId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$group) {
            return $this->asJson(['success' => false, 'error' => 'Group not found']);
        }

        $messages = ChatMessage::find()
            ->select(['message', 'MIN(created_at) as created_at'])
            ->where(['sender_id' => $currentUser->id])
            ->andWhere([
                'IN',
                'receiver_id',
                \common\models\Student::find()
                    ->select('user_id')
                    ->joinWith('enrollments')
                    ->where(['enrollment.group_id' => $groupId])
            ])
            ->groupBy(['message', 'DATE_FORMAT(FROM_UNIXTIME(created_at), "%Y-%m-%d %H:%i")'])
            ->orderBy(['created_at' => SORT_ASC])
            ->limit(30)
            ->asArray()
            ->all();

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'message' => $msg['message'],
                'is_mine' => true,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg['created_at']),
            ];
        }

        return $this->asJson(['success' => true, 'messages' => $result]);
    }

    /**
     * Clear history (Nomini o'zgartirdik: ClearChat -> ClearData)
     */
    public function actionClearData()
    {
        $currentUserId = Yii::$app->user->id;
        $otherUserId = Yii::$app->request->post('user_id');

        if (empty($otherUserId)) {
            return $this->asJson(['success' => false, 'error' => 'Invalid user ID']);
        }

        $deleted = ChatMessage::deleteAll([
            'or',
            ['sender_id' => $currentUserId, 'receiver_id' => $otherUserId],
            ['sender_id' => $otherUserId, 'receiver_id' => $currentUserId]
        ]);

        return $this->asJson([
            'success' => true,
            'deleted' => $deleted,
            'message' => 'Cleared successfully'
        ]);
    }

    /**
     * Clear group history (Nomini o'zgartirdik: ClearGroupChat -> ClearGroupData)
     */
    public function actionClearGroupData()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Only teachers can clear']);
        }

        $groupId = Yii::$app->request->post('group_id');

        if (empty($groupId)) {
            return $this->asJson(['success' => false, 'error' => 'Invalid group ID']);
        }

        $group = \common\models\Group::findOne([
            'id' => $groupId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$group) {
            return $this->asJson(['success' => false, 'error' => 'Group not found or access denied']);
        }

        $studentUserIds = \common\models\Student::find()
            ->select('user_id')
            ->joinWith('enrollments')
            ->where(['enrollment.group_id' => $groupId])
            ->column();

        if (empty($studentUserIds)) {
            return $this->asJson(['success' => true, 'deleted' => 0, 'message' => 'No messages to clear']);
        }

        $deleted = ChatMessage::deleteAll([
            'and',
            ['sender_id' => $currentUser->id],
            ['in', 'receiver_id', $studentUserIds]
        ]);

        return $this->asJson([
            'success' => true,
            'deleted' => $deleted,
            'message' => 'Group history cleared'
        ]);
    }
}