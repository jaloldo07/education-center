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
use common\models\Group;
use common\models\TeacherApplication;
use yii\web\UploadedFile;
use common\models\EnrollmentApplication;
use common\models\Enrollment;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                // 'payment' ni bu yerdan olib tashladik, chunki u endi PaymentControllerda
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

            // Check user before login
            $user = User::findByUsername($model->username);

            // Block admin/director from frontend
            if ($user && in_array($user->role, ['admin', 'director'])) {
                Yii::$app->session->setFlash('error', 'Access denied. Please use the Admin Panel to login.');
                return $this->refresh();
            }

            // Normal login for students/teachers
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
                'subtitle' => 'By ' . $course->teacher->full_name . ' - ' . number_format($course->price, 0) . ' UZS',
                'url' => \yii\helpers\Url::to(['/site/courses']), // Yoki course-detail
                'color' => 'primary'
            ];
        }

        // Teachers
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

    public function actionEnroll($id)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Please login to enroll.');
            return $this->redirect(['login']);
        }

        if (Yii::$app->user->identity->role !== 'student') {
            Yii::$app->session->setFlash('error', 'Only students can enroll in courses.');
            return $this->redirect(['courses']);
        }

        $course = Course::findOne($id);
        if (!$course) {
            throw new \yii\web\NotFoundHttpException('Course not found.');
        }

        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        if (!$student) {
            Yii::$app->session->setFlash('error', 'Student profile not found.');
            return $this->redirect(['courses']);
        }

        $groups = Group::find()
            ->where(['course_id' => $course->id])
            ->all();

        if (empty($groups)) {
            Yii::$app->session->setFlash('error', 'No groups available for this course yet.');
            return $this->redirect(['course-detail', 'id' => $id]);
        }

        $model = new EnrollmentApplication();
        $model->student_id = $student->id;
        $model->course_id = $course->id;

        if ($model->load(Yii::$app->request->post())) {

            // Check if already enrolled or applied
            $existingEnrollment = Enrollment::find()
                ->where(['student_id' => $student->id, 'group_id' => $model->group_id])
                ->one();

            if ($existingEnrollment) {
                // Agar avvalroq yaratilgan lekin to'lanmagan bo'lsa
                if ($existingEnrollment->status === Enrollment::STATUS_WAITING_PAYMENT) {
                    // 🔥 TUZATILDI: Endi yangi PaymentControllerga yo'naltiramiz
                    return $this->redirect(['/payment/create', 'course_id' => $course->id]);
                }
                
                Yii::$app->session->setFlash('error', 'You are already enrolled in this group!');
                return $this->refresh();
            }

            $existingApplication = EnrollmentApplication::find()
                ->where([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => EnrollmentApplication::STATUS_PENDING
                ])
                ->one();

            if ($existingApplication) {
                Yii::$app->session->setFlash('error', 'You already have a pending application for this course!');
                return $this->refresh();
            }

            // FREE COURSE yoki AUTOMATIC ENROLL logikasi
            // Hozircha baribir to'lov kerak degan mantiq bo'yicha:
            if ($course->isFree()) {
                $enrollment = new Enrollment();
                $enrollment->student_id = $student->id;
                $enrollment->group_id = $model->group_id;
                $enrollment->enrolled_on = date('Y-m-d');
                $enrollment->status = Enrollment::STATUS_WAITING_PAYMENT;

                if ($enrollment->save()) {
                    // 🔥 TUZATILDI: To'g'ridan-to'g'ri yangi to'lov sahifasiga
                    return $this->redirect(['/payment/create', 'course_id' => $course->id]);
                }
            }
            // PREMIUM COURSE
            else {
                $model->status = EnrollmentApplication::STATUS_PENDING;
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '✅ Application submitted! Wait for admin approval to pay.');
                    return $this->redirect(['/student/dashboard']);
                }
            }
        }

        return $this->render('enroll', [
            'model' => $model,
            'course' => $course,
            'groups' => $groups,
            'student' => $student,
        ]);
    }

    // 🔥 actionPayment O'CHIRILDI! Chunki endi PaymentController ishlaydi.
}