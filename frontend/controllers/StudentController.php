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
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->role === 'student';
                        }
                    ],
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

        // 1. Studentning barcha enrollmentlarini olamiz
        $enrollments = Enrollment::find()
            ->where(['student_id' => $student->id])
            ->with(['group', 'group.course', 'group.teacher'])
            ->all();

        // 2. To'lovlar tarixi (o'zgartirishsiz)
        $payments = Payment::find()
            ->where(['student_id' => $student->id])
            ->with('course')
            ->orderBy(['payment_date' => SORT_DESC])
            ->all();

        // 3. STATISTIKA (o'zgartirishsiz)
        $stats = [
            'totalEnrollments' => count($enrollments),
            'activeEnrollments' => Enrollment::find()->where(['student_id' => $student->id, 'status' => 'active'])->count(),
            'totalPayments' => Payment::find()->where(['student_id' => $student->id])->sum('amount') ?? 0,
        ];

        // --- YANGI QO'SHILGAN QISM: Dars Jadvalini olish ---
        
        // Student a'zo bo'lgan va statusi 'active' bo'lgan guruh IDlarini yig'amiz
        $activeGroupIds = [];
        foreach ($enrollments as $enrollment) {
            if ($enrollment->status === 'active') {
                $activeGroupIds[] = $enrollment->group_id;
            }
        }

        // Shu guruhlarga tegishli barcha dars jadvallarini topamiz
        $schedules = [];
        if (!empty($activeGroupIds)) {
            $schedules = Schedule::find()
                ->where(['group_id' => $activeGroupIds])
                ->with(['group', 'group.course', 'group.teacher']) // Qo'shimcha ma'lumotlarni ham olamiz
                ->orderBy(['day_of_week' => SORT_ASC, 'start_time' => SORT_ASC])
                ->all();
        }
        // ----------------------------------------------------

        return $this->render('dashboard', [
            'student' => $student,
            'enrollments' => $enrollments,
            'payments' => $payments,
            'stats' => $stats,
            'schedules' => $schedules, // <-- Viewga yuboryapmiz
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
