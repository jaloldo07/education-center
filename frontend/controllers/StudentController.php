<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Student;
use common\models\Enrollment;
use common\models\Payment;
use common\models\Notification;


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

        $enrollments = Enrollment::find()
            ->where(['student_id' => $student->id])
            ->with(['group', 'group.course', 'group.teacher'])
            ->all();

        $payments = Payment::find()
            ->where(['student_id' => $student->id])
            ->with('course')
            ->orderBy(['payment_date' => SORT_DESC])
            ->all();

        $stats = [
            'totalEnrollments' => count($enrollments),
            'activeEnrollments' => Enrollment::find()->where(['student_id' => $student->id, 'status' => 'active'])->count(),
            'totalPayments' => Payment::find()->where(['student_id' => $student->id])->sum('amount') ?? 0,
        ];

        return $this->render('dashboard', [
            'student' => $student,
            'enrollments' => $enrollments,
            'payments' => $payments,
            'stats' => $stats,
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
