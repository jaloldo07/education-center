<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\SignupForm;
use common\models\Course;
use common\models\Teacher;
use common\models\Student;
use common\models\Payment;
use common\models\User;
use yii\web\UploadedFile;
use common\models\TeacherApplication;
use common\models\Enrollment;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'], 
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
        $courses = Course::find()->with('teacher')->limit(6)->all();
        $teachers = Teacher::find()->orderBy(['rating' => SORT_DESC])->limit(4)->all();

        $stats = [
            'totalStudents' => Student::find()->count(),
            'totalCourses' => Course::find()->count(),
            'totalTeachers' => Teacher::find()->count(),
            'yearlyIncome' => Payment::find()
                ->where(['>=', 'payment_date', date('Y-01-01')])
                ->sum('amount') ?? 0,
        ];

        return $this->render('index', [
            'courses' => $courses,
            'teachers' => $teachers,
            'stats' => $stats,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {

            $user = User::findByUsername($model->username);

            if ($user && in_array($user->role, ['admin', 'director'])) {
                Yii::$app->session->setFlash('error', 'Access denied. Please use the Admin Panel to login.');
                return $this->refresh();
            }

            if ($model->login()) {
                $role = Yii::$app->user->identity->role;

                if ($role === 'teacher') {
                    return $this->redirect(['/teacher/dashboard']);
                } elseif ($role === 'student') {
                    return $this->redirect(['/student/dashboard']);
                }

                return $this->goHome();
            }
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please login.');
            return $this->redirect(['login']);
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionCourses()
    {
        $courses = Course::find()->with('teacher')->all();
        return $this->render('courses', ['courses' => $courses]);
    }

    public function actionTeacherDetail($id)
    {
        $teacher = Teacher::find()
            ->where(['id' => $id])
            ->one();

        if (!$teacher) {
            throw new \yii\web\NotFoundHttpException('Teacher not found.');
        }

        $courses = Course::find()
            ->where(['teacher_id' => $id])
            ->all();

        return $this->render('teacher-detail', [
            'teacher' => $teacher,
            'courses' => $courses,
        ]);
    }

    public function actionStudentPortal()
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
            if ($role === 'student') {
                return $this->redirect(['/student/dashboard']);
            }
        }

        return $this->render('student-portal');
    }

    public function actionTeachers()
    {
        $teachers = Teacher::find()->orderBy(['rating' => SORT_DESC])->all();
        return $this->render('teachers', ['teachers' => $teachers]);
    }

    public function actionTeacherRegister()
    {
        $model = new TeacherApplication();
        $model->status = TeacherApplication::STATUS_PENDING;

        if ($model->load(Yii::$app->request->post())) {
            $model->cvFileUpload = UploadedFile::getInstance($model, 'cvFileUpload');

            if ($model->cvFileUpload) {
                $model->upload();
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Your application has been submitted successfully! We will review it and contact you soon.');
                return $this->redirect(['site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to submit application. Please try again.');
            }
        }

        return $this->render('teacher-register', [
            'model' => $model,
        ]);
    }

    public function actionTeacherLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
            if ($role === 'teacher') {
                return $this->redirect(['/teacher/dashboard']);
            }
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = Yii::$app->user->identity;

            if ($user->role === 'teacher') {
                return $this->redirect(['/teacher/dashboard']);
            } else {
                Yii::$app->user->logout();
                Yii::$app->session->setFlash('error', 'This login is for teachers only.');
            }
        }

        return $this->render('teacher-login', [
            'model' => $model,
        ]);
    }

    public function actionSearch()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $query = Yii::$app->request->get('q', '');

        if (strlen($query) < 2) {
            return ['results' => []];
        }

        $results = [];

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
                'subtitle' => 'By ' . $course->teacher->full_name . ' - ' . number_format($course->price, 0) . ' UZS',
                'url' => \yii\helpers\Url::to(['/site/courses']),
                'color' => 'primary'
            ];
        }

        $teachers = Teacher::find()
            ->where(['like', 'full_name', $query])
            ->orWhere(['like', 'subject', $query])
            ->limit(5)
            ->all();

        foreach ($teachers as $teacher) {
            $results[] = [
                'type' => 'Teacher',
                'icon' => 'fa-chalkboard-teacher',
                'title' => $teacher->full_name,
                'subtitle' => $teacher->subject . ' - ' . $teacher->experience_years . ' years exp.',
                'url' => \yii\helpers\Url::to(['/site/teachers']),
                'color' => 'success'
            ];
        }

        return ['results' => $results];
    }

    public function actionCourseDetail($id)
    {
        $course = Course::find()
            ->where(['id' => $id])
            ->with(['teacher'])
            ->one();

        if (!$course) {
            throw new \yii\web\NotFoundHttpException('Course not found.');
        }

        return $this->render('course-detail', [
            'course' => $course,
        ]);
    }

    /**
     * 🔥 YANGI ENROLL MANTIG'I
     * Endi hech qanday forma yo'q. Talaba "Enroll" bossa to'g'ridan to'g'ri darsga o'tadi.
     * U yerdagi Controller (StudentLessonController) o'zi Free yoki Premium ni tekshirib oladi!
     */
    public function actionEnroll($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Iltimos, avval tizimga kiring (Login).');
            return $this->redirect(['login']);
        }

        if (Yii::$app->user->identity->role !== 'student') {
            Yii::$app->session->setFlash('error', 'Faqatgina talabalar kursga yozilishi mumkin.');
            return $this->redirect(['courses']);
        }

        // Barcha mashaqqatli tekshiruvlar tayyor qilib qo'yilgan joyga jo'natamiz
        return $this->redirect(['/student-lesson/course', 'course_id' => $id]);
    }
}