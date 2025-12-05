<?php

namespace backend\controllers;

use Yii;
use common\models\TeacherApplication;
use common\models\Teacher;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class TeacherApplicationController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TeacherApplication::find()
                ->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
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

        if ($application->status !== TeacherApplication::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'This application has already been processed.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // ✨ Username yaratish (ismning birinchi qismi, kichik harflar)
            $nameParts = explode(' ', $application->full_name);
            $firstName = strtolower($nameParts[0]);

            // Agar username band bo'lsa, raqam qo'shish
            $username = $firstName;
            $counter = 1;
            while (User::findOne(['username' => $username])) {
                $username = $firstName . $counter;
                $counter++;
            }

            // ✨ Password yaratish
            $password = $username . '123';

            // Create User
            $user = new User();
            $user->username = $username;
            $user->email = $application->email;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->role = User::ROLE_TEACHER;
            $user->status = User::STATUS_ACTIVE;

            if (!$user->save()) {
                throw new \Exception('Failed to create user account');
            }

            // Create Teacher
            $teacher = new Teacher();
            $teacher->full_name = $application->full_name;
            $teacher->subject = $application->subject;
            $teacher->experience_years = $application->experience_years;
            $teacher->phone = $application->phone;
            $teacher->email = $application->email;
            $teacher->bio = $application->bio;
            $teacher->rating = 5.0;

            if (!$teacher->save()) {
                throw new \Exception('Failed to create teacher profile');
            }

            // Update Application with credentials info
            $application->status = TeacherApplication::STATUS_APPROVED;
            $application->reviewed_by = Yii::$app->user->id;
            $application->reviewed_at = time();
            $application->admin_comment = "Account created:\nUsername: {$username}\nPassword: {$password}";
            $application->save();

            $transaction->commit();

            Yii::$app->session->setFlash('success', "✅ Application approved! Teacher account created:<br><strong>Username:</strong> {$username}<br><strong>Password:</strong> {$password}<br><br>⚠️ Please send these credentials to the teacher via email!");

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

        if ($application->status !== TeacherApplication::STATUS_PENDING) {
            Yii::$app->session->setFlash('error', 'This application has already been processed.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $application->status = TeacherApplication::STATUS_REJECTED;
        $application->reviewed_by = Yii::$app->user->id;
        $application->reviewed_at = time();

        if ($application->save()) {
            Yii::$app->session->setFlash('success', 'Application rejected.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Application deleted.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TeacherApplication::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Application not found.');
    }
}
