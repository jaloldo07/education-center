<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Student;
use common\models\Enrollment;
use common\models\Payment;
use common\models\Notification;
use common\models\Schedule;


class StudentController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@'], 'matchCallback' => function ($rule, $action) { return Yii::$app->user->identity->role === 'student'; }],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);

        if (!$student) {
            Yii::$app->session->setFlash('error', 'Student profile not found.');
            return $this->goHome();
        }

        // 1. Studentning barcha enrollmentlarini endi to'g'ridan-to'g'ri Course bilan olamiz
        $enrollments = Enrollment::find()
            ->where(['student_id' => $student->id])
            ->with(['course', 'course.teacher'])
            ->all();

        // 2. To'lovlar tarixi
        $payments = Payment::find()
            ->where(['student_id' => $student->id])
            ->with('course')
            ->orderBy(['payment_date' => SORT_DESC])
            ->all();

        // 3. STATISTIKA
        $stats = [
            'totalEnrollments' => count($enrollments),
            'activeEnrollments' => Enrollment::find()->where(['student_id' => $student->id, 'status' => 'active'])->count(),
            'totalPayments' => Payment::find()->where(['student_id' => $student->id])->sum('amount') ?? 0,
        ];

        // 4. Dars jadvali (Endi course_id orqali qidiramiz)
        $activeCourseIds = [];
        foreach ($enrollments as $enrollment) {
            if ($enrollment->status === 'active') {
                $activeCourseIds[] = $enrollment->course_id;
            }
        }

        $schedules = [];
        if (!empty($activeCourseIds)) {
            $schedules = Schedule::find()
                ->where(['course_id' => $activeCourseIds])
                ->with(['course', 'course.teacher'])
                ->orderBy(['day_of_week' => SORT_ASC, 'start_time' => SORT_ASC])
                ->all();
        }

        return $this->render('dashboard', [
            'student' => $student,
            'enrollments' => $enrollments,
            'payments' => $payments,
            'stats' => $stats,
            'schedules' => $schedules,
        ]);
    }

    public function actionMarkNotificationRead($id)
    {
        $notification = Notification::findOne([
            'id' => $id,
            'user_id' => Yii::$app->user->id
        ]);

        if ($notification) {
            $notification->markAsRead();
        }

        return $this->redirect(['dashboard']);
    }

    public function actionNotifications()
    {
        $notifications = Notification::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('notifications', [
            'notifications' => $notifications,
        ]);
    }
}
