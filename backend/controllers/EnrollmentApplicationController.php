<?php

namespace backend\controllers;

use Yii;
use common\models\EnrollmentApplication;
use common\models\Enrollment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\Notification;

class EnrollmentApplicationController extends Controller
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
                            if (Yii::$app->user->isGuest || !Yii::$app->user->identity) {
                                return false;
                            }
                            $role = Yii::$app->user->identity->role;
                            return in_array($role, ['director', 'admin']);
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => EnrollmentApplication::find()
                ->with(['student', 'course']) // 🔥 group olib tashlandi
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionApprove($id)
    {
        $application = $this->findModel($id);

        if ($application->status !== EnrollmentApplication::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'This application has already been processed.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Create Enrollment
            $enrollment = new Enrollment();
            $enrollment->student_id = $application->student_id;
            $enrollment->course_id = $application->course_id; // 🔥 group_id o'rniga course_id
            $enrollment->enrolled_on = date('Y-m-d');
            
            $enrollment->status = Enrollment::STATUS_WAITING_PAYMENT;

            if (!$enrollment->save()) {
                throw new \Exception('Failed to create enrollment');
            }

            // Update Application
            $application->status = EnrollmentApplication::STATUS_APPROVED;
            $application->reviewed_by = Yii::$app->user->id;
            $application->reviewed_at = time();
            $application->save();

            Notification::notify(
                $application->student->user_id,
                '✅ Application Approved!',
                'Your application for "' . $application->course->name . '" is approved. Please verify payment to start.',
                Notification::TYPE_SUCCESS,
                '/student/dashboard'
            );

            $transaction->commit();

            Yii::$app->session->setFlash('success', '✅ Application approved! Enrollment created (Waiting for Payment).');
            return $this->redirect(['view', 'id' => $id]);
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            return $this->redirect(['view', 'id' => $id]);
        }
    }

    public function actionReject($id)
    {
        $application = $this->findModel($id);

        if ($application->status !== EnrollmentApplication::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'This application has already been processed.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $application->status = EnrollmentApplication::STATUS_REJECTED;
        $application->reviewed_by = Yii::$app->user->id;
        $application->reviewed_at = time();

        if ($application->save()) {
            Notification::notify(
                $application->student->user_id,
                '❌ Enrollment Rejected',
                'Unfortunately, your enrollment application for "' . $application->course->name . '" has been rejected. Please contact administration for more information.',
                Notification::TYPE_DANGER
            );
            Yii::$app->session->setFlash('success', 'Application rejected.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDelete($id)
    {
        $application = $this->findModel($id);

        if ($application->status === EnrollmentApplication::STATUS_APPROVED) {
            $enrollment = Enrollment::findOne([
                'student_id' => $application->student_id,
                'course_id' => $application->course_id, // 🔥 group_id o'rniga course_id
            ]);
            if ($enrollment) {
                $enrollment->delete();
            }
        }

        $application->delete();

        Yii::$app->session->setFlash('success', 'Enrollment application has been deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = EnrollmentApplication::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Application not found.');
    }
}