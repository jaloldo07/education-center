<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Teacher;
use common\models\Group;
use common\models\Course;
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

        $groups = Group::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course', 'students'])
            ->all();

        $courses = Course::find()
            ->where(['teacher_id' => $teacher->id])
            ->all();

        $stats = [
            'totalGroups' => count($groups),
            'totalCourses' => count($courses),
            'totalStudents' => 0,
        ];

        foreach ($groups as $group) {
            $stats['totalStudents'] += count($group->students);
        }

        return $this->render('dashboard', [
            'teacher' => $teacher,
            'groups' => $groups,
            'courses' => $courses,
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
        $groups = Group::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['students', 'course'])
            ->all();

        foreach ($groups as $group) {
            foreach ($group->students as $student) {
                if (!isset($students[$student->id])) {
                    $students[$student->id] = [
                        'student' => $student,
                        'groups' => [],
                    ];
                }
                $students[$student->id]['groups'][] = $group;
            }
        }

        return $this->render('my-students', [
            'teacher' => $teacher,
            'students' => $students,
            'totalStudents' => count($students),
        ]);
    }

    public function actionGroup($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::find()
            ->where(['id' => $id, 'teacher_id' => $teacher->id])
            ->with(['course', 'students'])
            ->one();

        if (!$group) {
            throw new \yii\web\NotFoundHttpException('Group not found or access denied.');
        }

        return $this->render('group', [
            'group' => $group,
            'teacher' => $teacher,
        ]);
    }

    public function actionAttendance($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::find()
            ->where(['id' => $id, 'teacher_id' => $teacher->id])
            ->with(['course', 'students'])
            ->one();

        if (!$group) {
            throw new \yii\web\NotFoundHttpException('Group not found.');
        }

        $date = Yii::$app->request->get('date', date('Y-m-d'));

        $attendances = [];
        foreach ($group->students as $student) {
            $attendance = Attendance::find()
                ->where([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'attendance_date' => $date,
                ])
                ->one();

            $attendances[$student->id] = $attendance;
        }

        return $this->render('attendance', [
            'group' => $group,
            'teacher' => $teacher,
            'date' => $date,
            'attendances' => $attendances,
        ]);
    }

    public function actionSaveAttendance()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $rawBody = Yii::$app->request->getRawBody();
        $data = json_decode($rawBody, true);

        if (!$data) {
            return ['success' => false, 'message' => 'Invalid data'];
        }

        $groupId = $data['group_id'] ?? null;
        $date = $data['date'] ?? null;
        $attendanceData = $data['attendance'] ?? [];

        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::findOne(['id' => $groupId, 'teacher_id' => $teacher->id]);
        if (!$group) {
            return ['success' => false, 'message' => 'Unauthorized'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($attendanceData as $studentId => $status) {
                $attendance = Attendance::find()
                    ->where([
                        'student_id' => $studentId,
                        'group_id' => $groupId,
                        'attendance_date' => $date,
                    ])
                    ->one();

                if ($attendance) {
                    $attendance->status = $status;
                } else {
                    $attendance = new Attendance();
                    $attendance->student_id = $studentId;
                    $attendance->group_id = $groupId;
                    $attendance->attendance_date = $date;
                    $attendance->status = $status;
                }

                if (!$attendance->save()) {
                    throw new \Exception('Failed to save attendance for student ' . $studentId);
                }
            }

            $transaction->commit();
            return ['success' => true, 'message' => 'Attendance saved successfully!'];
        } catch (\Exception $e) {
            $transaction->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function actionAttendanceHistory($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::find()
            ->where(['id' => $id, 'teacher_id' => $teacher->id])
            ->with(['course', 'students'])
            ->one();

        if (!$group) {
            throw new \yii\web\NotFoundHttpException('Group not found.');
        }

        $attendances = Attendance::find()
            ->where(['group_id' => $id])
            ->with('student')
            ->orderBy(['attendance_date' => SORT_DESC])
            ->all();

        $attendanceByDate = [];
        foreach ($attendances as $attendance) {
            $attendanceByDate[$attendance->attendance_date][] = $attendance;
        }

        return $this->render('attendance-history', [
            'group' => $group,
            'teacher' => $teacher,
            'attendanceByDate' => $attendanceByDate,
        ]);
    }

    public function actionSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::find()
            ->where(['id' => $id, 'teacher_id' => $teacher->id])
            ->with(['course'])
            ->one();

        if (!$group) {
            throw new \yii\web\NotFoundHttpException('Group not found.');
        }

        $schedules = Schedule::find()
            ->where(['group_id' => $id, 'teacher_id' => $teacher->id])
            ->orderBy(['day_of_week' => SORT_ASC, 'start_time' => SORT_ASC])
            ->all();

        return $this->render('schedule', [
            'group' => $group,
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    public function actionCreateSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $group = Group::findOne(['id' => $id, 'teacher_id' => $teacher->id]);
        if (!$group) {
            throw new \yii\web\NotFoundHttpException('Group not found.');
        }

        $model = new Schedule();
        $model->group_id = $id;
        $model->teacher_id = $teacher->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Schedule created successfully!');
            return $this->redirect(['schedule', 'id' => $id]);
        }

        return $this->render('create-schedule', [
            'model' => $model,
            'group' => $group,
        ]);
    }

    public function actionDeleteSchedule($id)
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $schedule = Schedule::findOne(['id' => $id, 'teacher_id' => $teacher->id]);
        if ($schedule) {
            $groupId = $schedule->group_id;
            $schedule->delete();
            Yii::$app->session->setFlash('success', 'Schedule deleted.');
            return $this->redirect(['schedule', 'id' => $groupId]);
        }

        throw new \yii\web\NotFoundHttpException();
    }

    public function actionCalendar()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        $schedules = Schedule::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['group', 'group.course'])
            ->all();

        return $this->render('calendar', [
            'teacher' => $teacher,
            'schedules' => $schedules,
        ]);
    }

    // ==================== TEST MANAGEMENT ====================

    public function actionTests()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        if (!$teacher) {
            throw new \yii\web\NotFoundHttpException('Teacher profile not found.');
        }

        $tests = Test::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course', 'group'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('tests/index', [
            'teacher' => $teacher,
            'tests' => $tests,
        ]);
    }
}