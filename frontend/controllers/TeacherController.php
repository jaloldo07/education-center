<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Teacher;
use common\models\Course;
use common\models\Enrollment; // Studentlarni topish uchun
use common\models\Attendance;
use common\models\Schedule;
use common\models\Test;
use common\models\TestQuestion;
use common\models\TestAttempt;

class TeacherController extends Controller
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
                            return Yii::$app->user->identity->role === 'teacher';
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        if (!$teacher) {
            Yii::$app->session->setFlash('error', 'Teacher profile not found.');
            return $this->goHome();
        }

        $courses = Course::find()
            ->where(['teacher_id' => $teacher->id])
            ->all();

        // O'qituvchining dars jadvallarini to'g'ridan to'g'ri kurs orqali tortib olamiz
        $schedules = Schedule::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course'])
            ->orderBy(['day_of_week' => SORT_ASC, 'start_time' => SORT_ASC])
            ->all();

        // O'qituvchiga tegishli jami noyob (unique) talabalarni sanash
        $enrollments = Enrollment::find()
            ->joinWith('course')
            ->where(['course.teacher_id' => $teacher->id, 'enrollment.status' => Enrollment::STATUS_ACTIVE])
            ->all();
            
        $uniqueStudents = [];
        foreach ($enrollments as $enrollment) {
            $uniqueStudents[$enrollment->student_id] = true;
        }

        $stats = [
            'totalCourses' => count($courses),
            'totalSchedules' => count($schedules),
            'totalStudents' => count($uniqueStudents),
        ];

        return $this->render('dashboard', [
            'teacher' => $teacher,
            'courses' => $courses,
            'schedules' => $schedules,
            'stats' => $stats,
        ]);
    }

    public function actionMyStudents()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        if (!$teacher) {
            throw new \yii\web\NotFoundHttpException('Teacher profile not found.');
        }

        $students = [];
        $enrollments = Enrollment::find()
            ->joinWith(['course', 'student'])
            ->where(['course.teacher_id' => $teacher->id, 'enrollment.status' => Enrollment::STATUS_ACTIVE])
            ->all();

        foreach ($enrollments as $enrollment) {
            $studentId = $enrollment->student_id;
            if (!isset($students[$studentId])) {
                $students[$studentId] = [
                    'student' => $enrollment->student,
                    'courses' => [],
                ];
            }
            $students[$studentId]['courses'][] = $enrollment->course;
        }

        return $this->render('my-students', [
            'teacher' => $teacher,
            'students' => $students,
            'totalStudents' => count($students),
        ]);
    }

    // actionGroup endi actionCourse bo'ldi
    public function actionCourse($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $course = Course::find()
            ->where(['id' => $id, 'teacher_id' => $teacher->id])
            ->one();

        if (!$course) {
            throw new \yii\web\NotFoundHttpException('Course not found or access denied.');
        }

        return $this->render('course', [ // group.php o'rniga course.php bo'ladi
            'course' => $course,
            'teacher' => $teacher,
        ]);
    }

    public function actionAttendance($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $course = Course::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$course) throw new \yii\web\NotFoundHttpException('Course not found.');

        $date = Yii::$app->request->get('date', date('Y-m-d'));
        
        $enrollments = Enrollment::find()->where(['course_id' => $course->id, 'status' => 'active'])->with('student')->all();

        $attendances = [];
        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            $attendance = Attendance::find()
                ->where([
                    'student_id' => $student->id,
                    'course_id' => $course->id, // group_id o'rniga course_id
                    'attendance_date' => $date,
                ])
                ->one();
            $attendances[$student->id] = $attendance;
        }

        return $this->render('attendance', [
            'course' => $course,
            'teacher' => $teacher,
            'date' => $date,
            'attendances' => $attendances,
            'enrollments' => $enrollments,
        ]);
    }

    public function actionSaveAttendance()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);
        if (!$data) return ['success' => false, 'message' => 'Invalid data'];

        $courseId = $data['course_id'] ?? null; // group_id o'rniga
        $date = $data['date'] ?? null;
        $attendanceData = $data['attendance'] ?? [];

        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $course = Course::findOne(['id' => $courseId, 'teacher_id' => $teacher->id]);
        
        if (!$course) return ['success' => false, 'message' => 'Unauthorized'];

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($attendanceData as $studentId => $status) {
                $attendance = Attendance::find()
                    ->where([
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'attendance_date' => $date,
                    ])->one();

                if ($attendance) {
                    $attendance->status = $status;
                } else {
                    $attendance = new Attendance();
                    $attendance->student_id = $studentId;
                    $attendance->course_id = $courseId;
                    $attendance->attendance_date = $date;
                    $attendance->status = $status;
                }

                if (!$attendance->save()) throw new \Exception('Failed to save');
            }
            $transaction->commit();
            return ['success' => true, 'message' => 'Attendance saved!'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function actionAttendanceHistory($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $course = Course::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$course) throw new \yii\web\NotFoundHttpException('Course not found.');

        $attendances = Attendance::find()
            ->where(['course_id' => $id])
            ->with('student')
            ->orderBy(['attendance_date' => SORT_DESC])
            ->all();

        $attendanceByDate = [];
        foreach ($attendances as $attendance) {
            $attendanceByDate[$attendance->attendance_date][] = $attendance;
        }

        return $this->render('attendance-history', [
            'course' => $course,
            'teacher' => $teacher,
            'attendanceByDate' => $attendanceByDate,
        ]);
    }

    public function actionSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $course = Course::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$course) throw new \yii\web\NotFoundHttpException('Course not found.');

        $schedules = Schedule::find()
            ->where(['course_id' => $id, 'teacher_id' => $teacher->id])
            ->orderBy(['day_of_week' => SORT_ASC, 'start_time' => SORT_ASC])
            ->all();

        return $this->render('schedule', [
            'course' => $course,
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    public function actionCreateSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $course = Course::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$course) throw new \yii\web\NotFoundHttpException('Course not found.');

        $model = new Schedule();
        $model->course_id = $id;
        $model->teacher_id = $teacher->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Schedule created successfully!');
            return $this->redirect(['schedule', 'id' => $id]);
        }

        return $this->render('create-schedule', [
            'model' => $model,
            'course' => $course,
        ]);
    }

    public function actionDeleteSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $schedule = Schedule::findOne(['id' => $id, 'teacher_id' => $teacher->id]);
        
        if ($schedule) {
            $courseId = $schedule->course_id;
            $schedule->delete();
            Yii::$app->session->setFlash('success', 'Schedule deleted.');
            return $this->redirect(['schedule', 'id' => $courseId]);
        }
        throw new \yii\web\NotFoundHttpException();
    }

    public function actionCalendar()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        $schedules = Schedule::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course'])
            ->all();

        return $this->render('calendar', [
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    public function actionTests()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);
        if (!$teacher) throw new \yii\web\NotFoundHttpException('Teacher profile not found.');

        $tests = Test::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course']) // group o'chirildi
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('tests/index', [
            'teacher' => $teacher,
            'tests' => $tests,
        ]);
    }
}