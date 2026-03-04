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
            // 1. Username yaratish
            $nameParts = explode(' ', $application->full_name);
            $firstName = strtolower($nameParts[0]);
            $firstName = preg_replace('/[^a-z0-9]/', '', $firstName); // Faqat harf va raqam qolsin

            $username = $firstName;
            $counter = 1;
            while (User::findOne(['username' => $username])) {
                $username = $firstName . $counter;
                $counter++;
            }

            // 2. Password yaratish
            $password = Yii::$app->security->generateRandomString(8);

            // 3. User yaratish
            $user = new User();
            $user->username = $username;
            $user->email = $application->email;
            $user->setPassword($password);
            $user->generateAuthKey();
            $user->role = User::ROLE_TEACHER;
            $user->status = User::STATUS_ACTIVE;

            if (!$user->save()) {
                throw new \Exception('Failed to create user account: ' . print_r($user->errors, true));
            }

            // 4. Teacher profilini yaratish
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->full_name = $application->full_name;
            $teacher->subject = $application->subject;
            $teacher->experience_years = $application->experience_years;
            $teacher->phone = $application->phone;
            $teacher->email = $application->email;
            $teacher->bio = $application->bio;
            $teacher->rating = 0; // Boshlang'ich reyting 0 (keyin o'zgartirasiz)

            if (!$teacher->save()) {
                throw new \Exception('Failed to create teacher profile: ' . print_r($teacher->errors, true));
            }

            // 5. Application statusini yangilash
            $application->status = TeacherApplication::STATUS_APPROVED;
            $application->reviewed_by = Yii::$app->user->id;
            $application->reviewed_at = time();
            $application->admin_comment = "Account created & Email sent."; // Izohni o'zgartirdik
            $application->save(false);

            // 6. EMAIL JO'NATISH (Yangi qism)
            $emailSent = false;
            try {
                // Agar haqiqiy serverda bo'lsa, bu xat ketadi.
                // Agar lokalda 'useFileTransport' => true bo'lsa, runtime/mail papkasiga tushadi.
                $sent = Yii::$app->mailer->compose()
                    ->setFrom(['noreply@education-center.uz' => 'Education Center'])
                    ->setTo($application->email)
                    ->setSubject('Congratulations! You are hired.')
                    ->setHtmlBody("
                        <div style='font-family: Arial, sans-serif; color: #333;'>
                            <h2 style='color: #4361ee;'>Welcome to the Team, {$teacher->full_name}!</h2>
                            <p>Your application has been approved. You can now login to the Education Center system.</p>
                            <div style='background: #f4f6f8; padding: 15px; border-radius: 8px; margin: 20px 0;'>
                                <p style='margin: 5px 0;'><strong>Username:</strong> {$username}</p>
                                <p style='margin: 5px 0;'><strong>Password:</strong> {$password}</p>
                            </div>
                            <p>Please login and update your profile (photo, password) immediately.</p>
                            <a href='http://education-center.local/site/login' style='background: #4361ee; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Login Now</a>
                        </div>
                    ")
                    ->send();
                
                if ($sent) $emailSent = true;
                
            } catch (\Exception $e) {
                // Xat ketmasa ham tranzaksiyani to'xtatmaymiz, lekin xabar beramiz
                Yii::error("Email sending failed: " . $e->getMessage());
            }

            $transaction->commit();

            // Xabarni shakllantirish
            $msg = "✅ Teacher account created successfully!";
            if ($emailSent) {
                $msg .= "<br>📧 Login credentials have been sent to <strong>{$application->email}</strong>.";
            } else {
                $msg .= "<br>⚠️ Email could not be sent. Please send these credentials manually:<br>Username: <strong>{$username}</strong><br>Password: <strong>{$password}</strong>";
            }

            Yii::$app->session->setFlash('success', $msg);

            // Tahrirlash sahifasiga yo'naltirish
            return $this->redirect(['/teacher/update', 'id' => $teacher->id]);

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