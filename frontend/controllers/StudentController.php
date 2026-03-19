<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Student;
use common\models\Enrollment;
use common\models\EnrollmentApplication; // 🔥 Arizalarni tortish uchun qo'shildi
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

        // 1. Faol kurslar
        $enrollments = Enrollment::find()
            ->where(['student_id' => $student->id])
            ->with(['course', 'course.teacher'])
            ->all();

        // 🔥 2. KUTILAYOTGAN YAKI RAD ETILGAN ARIZALAR
        $applications = EnrollmentApplication::find()
            ->where(['student_id' => $student->id])
            ->with('course')
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // 3. To'lovlar tarixi
        $payments = Payment::find()
            ->where(['student_id' => $student->id])
            ->with('course')
            ->orderBy(['payment_date' => SORT_DESC])
            ->all();

        // 4. STATISTIKA
        $stats = [
            'totalEnrollments' => count($enrollments),
            'activeEnrollments' => Enrollment::find()->where(['student_id' => $student->id, 'status' => 'active'])->count(),
            'totalPayments' => Payment::find()->where(['student_id' => $student->id])->sum('amount') ?? 0,
            'pendingApps' => EnrollmentApplication::find()->where(['student_id' => $student->id, 'status' => 'pending'])->count(), // 🔥 Kutilayotgan arizalar soni
        ];

        // 5. Dars jadvali
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
            'applications' => $applications, // 🔥 Viewga yuborildi
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