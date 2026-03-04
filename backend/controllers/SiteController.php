<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use common\models\User;
use common\models\Student;
use common\models\Teacher;
use common\models\Course;
use common\models\Group;
use common\models\Payment;


class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'search'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->redirect(['/dashboard/index']);
    }

    public function actionLogin()
    {
        $this->layout = 'blank';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            // ✅ Check user before login
            $user = User::findByUsername($model->username);

            // ❌ Block student/teacher from backend
            if ($user && in_array($user->role, ['student', 'teacher'])) {
                Yii::$app->session->setFlash('error', 'Access denied. This panel is for administrators only.');
                return $this->refresh();
            }

            //login for admin/director
            if ($model->login()) {
                return $this->redirect(['/dashboard/index']);
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = Yii::$app->request->get('q', '');

        if (strlen($query) < 2) {
            return ['results' => []];
        }

        $results = [];

        try {
            // Students
            $students = Student::find()
                ->where(['like', 'full_name', $query])
                ->orWhere(['like', 'email', $query])
                ->orWhere(['like', 'phone', $query])
                ->limit(5)
                ->all();

            foreach ($students as $student) {
                $results[] = [
                    'type' => 'Student',
                    'icon' => 'fa-user-graduate',
                    'title' => $student->full_name,
                    'subtitle' => $student->email,
                    'url' => \yii\helpers\Url::to(['/student/view', 'id' => $student->id]),
                    'color' => 'primary'
                ];
            }

            // Teachers
            $teachers = Teacher::find()
                ->where(['like', 'full_name', $query])
                ->orWhere(['like', 'email', $query])
                ->orWhere(['like', 'subject', $query])
                ->limit(5)
                ->all();

            foreach ($teachers as $teacher) {
                $results[] = [
                    'type' => 'Teacher',
                    'icon' => 'fa-chalkboard-teacher',
                    'title' => $teacher->full_name,
                    'subtitle' => $teacher->subject,
                    'url' => \yii\helpers\Url::to(['/teacher/view', 'id' => $teacher->id]),
                    'color' => 'success'
                ];
            }

            // Courses 
            $courses = Course::find()
                ->where(['like', 'name', $query])
                ->orWhere(['like', 'description', $query])
                ->with('teacher')
                ->limit(5)
                ->all();

            foreach ($courses as $course) {
                $results[] = [
                    'type' => 'Course',
                    'icon' => 'fa-book',
                    'title' => $course->name,
                    'subtitle' => 'Teacher: ' . ($course->teacher ? $course->teacher->full_name : 'Not assigned'), 
                    'url' => \yii\helpers\Url::to(['/course/view', 'id' => $course->id]),
                    'color' => 'info'
                ];
            }

            // Groups 
            $groups = Group::find()
                ->where(['like', 'name', $query])
                ->with(['course', 'teacher'])
                ->limit(5)
                ->all();

            foreach ($groups as $group) {
                $results[] = [
                    'type' => 'Group',
                    'icon' => 'fa-users',
                    'title' => $group->name,
                    'subtitle' => ($group->course ? $group->course->name : 'No course'), 
                    'url' => \yii\helpers\Url::to(['/group/view', 'id' => $group->id]),
                    'color' => 'warning'
                ];
            }

            // Payments 
            $payments = Payment::find()
                ->joinWith('student')
                ->where(['like', 'student.full_name', $query])
                ->with(['student', 'course'])
                ->limit(5)
                ->all();

            foreach ($payments as $payment) {
                $studentName = ($payment->student ? $payment->student->full_name : 'Unknown'); 
                $results[] = [
                    'type' => 'Payment',
                    'icon' => 'fa-money-bill-wave',
                    'title' => 'Payment #' . $payment->id,
                    'subtitle' => $studentName . ' - ' . number_format($payment->amount, 0) . ' UZS',
                    'url' => \yii\helpers\Url::to(['/payment/view', 'id' => $payment->id]),
                    'color' => 'danger'
                ];
            }

        } catch (\Exception $e) {
            // Added error handling
            Yii::error('Search error: ' . $e->getMessage(), __METHOD__);
            return ['results' => [], 'error' => 'Search failed'];
        }

        return ['results' => $results];
    }
}
