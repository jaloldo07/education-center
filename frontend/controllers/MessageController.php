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
     * Kontaktlarni olish (Talabalar o'qituvchilarini, o'qituvchilar o'z talabalarini ko'radi)
     */
    public function actionGetContacts()
    {
        $currentUser = Yii::$app->user->identity;
        $contacts = [];

        if ($currentUser->teacher) {
            $teacher = $currentUser->teacher;

            // 🔥 O'qituvchining kurslariga a'zo bo'lgan talabalarni olish
            $students = \common\models\Student::find()
                ->joinWith(['enrollments' => function ($query) use ($teacher) {
                    $query->joinWith('course')
                          ->where(['course.teacher_id' => $teacher->id, 'enrollment.status' => 'active']);
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

            // 🔥 Talaba o'qiyotgan kurslarning o'qituvchilarini olish
            $teachers = \common\models\Teacher::find()
                ->joinWith(['courses' => function ($query) use ($student) {
                    $query->joinWith('enrollments')
                          ->where(['enrollment.student_id' => $student->id, 'enrollment.status' => 'active']);
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

    public function actionGetMessages($userId)
    {
        $currentUserId = Yii::$app->user->id;
        $messages = ChatMessage::getConversation($currentUserId, $userId);

        ChatMessage::markAsRead($userId, $currentUserId);

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id' => $msg->id,
                'message' => $msg->message,
                'is_mine' => $msg->sender_id == $currentUserId,
                'is_course' => (bool)$msg->is_course_message, // group o'rniga course
                'course_id' => $msg->course_id,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg->created_at),
            ];
        }

        return ['success' => true, 'messages' => $result];
    }

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

        ChatMessage::markAsRead($userId, $currentUserId);

        $result = [];
        foreach ($messages as $msg) {
            $result[] = [
                'id' => $msg->id,
                'message' => $msg->message,
                'is_mine' => $msg->sender_id == $currentUserId,
                'is_course' => (bool)$msg->is_course_message,
                'course_id' => $msg->course_id,
                'created_at' => Yii::$app->formatter->asRelativeTime($msg->created_at),
            ];
        }

        return ['success' => true, 'messages' => $result];
    }

    public function actionCount()
    {
        $currentUserId = Yii::$app->user->id;

        $count = ChatMessage::find()
            ->where(['receiver_id' => $currentUserId, 'is_read' => 0])
            ->count();

        return ['success' => true, 'count' => $count];
    }

    /**
     * O'qituvchining KURSLARI (Guruh o'rniga)
     */
    public function actionGetCourses()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $teacher = $currentUser->teacher;

        $courses = \common\models\Course::find()
            ->where(['teacher_id' => $teacher->id])
            ->all();

        $result = [];
        foreach ($courses as $course) {
            $studentCount = \common\models\Enrollment::find()
                ->where(['course_id' => $course->id, 'status' => 'active'])
                ->count();

            $lastMessage = \common\models\ChatMessage::find()
                ->where([
                    'sender_id' => $currentUser->id,
                    'is_course_message' => 1,
                    'course_id' => $course->id
                ])
                ->orderBy(['created_at' => SORT_DESC])
                ->one();

            $result[] = [
                'id' => $course->id,
                'name' => $course->name,
                'student_count' => $studentCount,
                'last_message' => $lastMessage ? Yii::$app->formatter->asRelativeTime($lastMessage->created_at) : 'No messages',
            ];
        }

        return $this->asJson(['success' => true, 'courses' => $result]); // groups o'rniga courses
    }

    /**
     * Barcha talabalarga kurs orqali xabar yuborish
     */
    public function actionSendCourseMessage()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $courseId = Yii::$app->request->post('course_id');
        $message = Yii::$app->request->post('message');

        if (empty($courseId) || empty($message)) {
            return $this->asJson(['success' => false, 'error' => 'Invalid data']);
        }

        $course = \common\models\Course::findOne([
            'id' => $courseId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$course) {
            return $this->asJson(['success' => false, 'error' => 'Course not found']);
        }

        $students = \common\models\Student::find()
            ->joinWith('enrollments')
            ->where(['enrollment.course_id' => $courseId, 'enrollment.status' => 'active'])
            ->all();

        if (empty($students)) {
            return $this->asJson(['success' => false, 'error' => 'No students in course']);
        }

        $sentCount = 0;
        foreach ($students as $student) {
            $chat = new ChatMessage();
            $chat->sender_id = $currentUser->id;
            $chat->receiver_id = $student->user_id;
            $chat->message = $message;
            $chat->is_course_message = 1; // 🔥 group_id o'rniga
            $chat->course_id = $courseId;

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
     * Kurs tarixini olish
     */
    public function actionGetCourseMessages($courseId)
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Not a teacher']);
        }

        $course = \common\models\Course::findOne([
            'id' => $courseId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$course) {
            return $this->asJson(['success' => false, 'error' => 'Course not found']);
        }

        $messages = ChatMessage::find()
            ->select(['message', 'MIN(created_at) as created_at'])
            ->where([
                'sender_id' => $currentUser->id,
                'is_course_message' => 1,
                'course_id' => $courseId
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
     * Kurs xabarlarini tozalash
     */
    public function actionClearCourseData()
    {
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser->teacher) {
            return $this->asJson(['success' => false, 'error' => 'Only teachers can clear']);
        }

        $courseId = Yii::$app->request->post('course_id');

        if (empty($courseId)) {
            return $this->asJson(['success' => false, 'error' => 'Invalid course ID']);
        }

        $course = \common\models\Course::findOne([
            'id' => $courseId,
            'teacher_id' => $currentUser->teacher->id
        ]);

        if (!$course) {
            return $this->asJson(['success' => false, 'error' => 'Course not found or access denied']);
        }

        // O'qituvchining ushbu kurs bo'yicha yuborgan barcha xabarlarini o'chirish
        $deleted = ChatMessage::deleteAll([
            'sender_id' => $currentUser->id,
            'is_course_message' => 1,
            'course_id' => $courseId
        ]);

        return $this->asJson([
            'success' => true,
            'deleted' => $deleted,
            'message' => 'Course history cleared'
        ]);
    }
}