<?php

namespace backend\controllers;

use Yii;
use common\models\Student;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                            // ✅ FIXED: Added null check
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
            'query' => Student::find()->with('user')->orderBy(['created_at' => SORT_DESC]),
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

    public function actionCreate()
    {
        $model = new Student();
        $model->enrolled_date = date('Y-m-d');

        if ($model->load(Yii::$app->request->post())) {

            // ✅ FIXED: Generate unique username
            $user = new User();
            $baseUsername = strtolower(str_replace(' ', '', $model->full_name));
            $username = $this->generateUniqueUsername($baseUsername);
            
            $user->username = $username;
            $user->email = $model->email;
            
            // ✅ IMPROVED: Generate random secure password
            $randomPassword = Yii::$app->security->generateRandomString(12);
            $user->setPassword($randomPassword);
            
            $user->generateAuthKey();
            $user->role = User::ROLE_STUDENT;
            $user->status = User::STATUS_ACTIVE;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($user->save()) {
                    $model->user_id = $user->id;
                    if ($model->save()) {
                        $transaction->commit();
                        
                        // ✅ Show password to admin (they need to give it to student)
                        Yii::$app->session->setFlash('success', 
                            'Student created successfully!<br>' .
                            '<strong>Username:</strong> ' . $user->username . '<br>' .
                            '<strong>Password:</strong> ' . $randomPassword . '<br>' .
                            '<em>(Please save this password and give it to the student)</em>'
                        );
                        
                        // ✅ Log the creation
                        Yii::info("Student created: ID={$model->id}, Username={$user->username}", __METHOD__);
                        
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
                $transaction->rollBack();
                
                // ✅ Show validation errors
                $errors = array_merge($user->getErrors(), $model->getErrors());
                $errorMessage = 'Failed to save student: ' . implode(', ', array_map(function($err) {
                    return implode(', ', $err);
                }, $errors));
                
                Yii::$app->session->setFlash('error', $errorMessage);
                Yii::error($errorMessage, __METHOD__);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
                Yii::error('Student creation error: ' . $e->getMessage(), __METHOD__);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Student has been updated successfully.');
                    Yii::info("Student updated: ID={$model->id}", __METHOD__);
                    return $this->redirect(['view', 'id' => $model->id]);
                } else {
                    // Show validation errors
                    $errors = implode(', ', array_map(function($err) {
                        return implode(', ', $err);
                    }, $model->getErrors()));
                    Yii::$app->session->setFlash('error', 'Validation failed: ' . $errors);
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Error updating student: ' . $e->getMessage());
                Yii::error('Student update error: ' . $e->getMessage(), __METHOD__);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * ✅ FIXED: Properly delete both student and user
     */
    public function actionDelete($id)
    {
        $student = $this->findModel($id);
        $userId = $student->user_id;
        $studentName = $student->full_name;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // First delete the student
            if (!$student->delete()) {
                throw new \Exception('Failed to delete student record');
            }
            
            // Then delete the associated user
            if ($userId) {
                $user = User::findOne($userId);
                if ($user && !$user->delete()) {
                    throw new \Exception('Failed to delete user record');
                }
            }
            
            $transaction->commit();
            
            Yii::$app->session->setFlash('success', 'Student and associated user have been deleted successfully.');
            Yii::info("Student deleted: ID={$id}, Name={$studentName}", __METHOD__);
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Error deleting student: ' . $e->getMessage());
            Yii::error('Student deletion error: ' . $e->getMessage(), __METHOD__);
        }
        
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * ✅ NEW: Generate unique username
     * @param string $baseUsername Base username (from full name)
     * @return string Unique username
     */
    protected function generateUniqueUsername($baseUsername)
    {
        // Try with random 3-digit number first
        $username = $baseUsername . rand(100, 999);
        
        if (!User::findOne(['username' => $username])) {
            return $username;
        }
        
        // If that exists, try with 4-digit number
        for ($i = 0; $i < 10; $i++) {
            $username = $baseUsername . rand(1000, 9999);
            if (!User::findOne(['username' => $username])) {
                return $username;
            }
        }
        
        // Last resort: use timestamp
        $username = $baseUsername . time();
        
        // If even that exists (very unlikely), add counter
        $counter = 1;
        while (User::findOne(['username' => $username])) {
            $username = $baseUsername . time() . $counter++;
        }
        
        return $username;
    }
}
