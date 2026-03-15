<?php

namespace frontend\controllers;

use Yii;
use common\models\Lesson;
use common\models\LessonProgress;
use common\models\Student;
use common\models\Enrollment;
use common\models\Course;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\CourseTest;

class StudentLessonController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [['allow' => true, 'roles' => ['@']]],
            ],
        ];
    }

    public function actionIndex()
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        if (!$student) return $this->redirect(['/site/index']);

        // Endi to'g'ridan-to'g'ri course ni chaqiramiz
        $enrollments = Enrollment::find()
            ->joinWith('course')
            ->where(['enrollment.student_id' => $student->id, 'enrollment.status' => 'active'])
            ->all();

        return $this->render('courses', ['enrollments' => $enrollments]);
    }

    public function actionCourse($course_id)
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        if (!$student) return $this->redirect(['/site/index']);

        $course = Course::findOne($course_id);
        if (!$course) throw new NotFoundHttpException('Course not found.');

        // 1. Talabaning shu kursga yozilganligini tekshiramiz
        $enrollment = Enrollment::findOne([
            'student_id' => $student->id,
            'course_id' => $course_id
        ]);

        // 🔥 TEKIN VA PULLIK MANTIG'I 🔥
        if (!$enrollment) {
            if ($course->isFree()) {
                // Tekin kurs: Avtomatik yozamiz va darslarni ochamiz
                $enrollment = new Enrollment([
                    'student_id' => $student->id,
                    'course_id' => $course_id,
                    'enrolled_on' => date('Y-m-d H:i:s'),
                    'status' => Enrollment::STATUS_ACTIVE
                ]);
                $enrollment->save(false);
            } else {
                // Pullik kurs: To'lov sahifasiga yo'naltiramiz
                Yii::$app->session->setFlash('info', Yii::t('app', 'Bu premium kurs. Davom etish uchun to\'lovni amalga oshiring.'));
                return $this->redirect(['/payment/create', 'course_id' => $course_id]);
            }
        } elseif ($enrollment->status !== Enrollment::STATUS_ACTIVE) {
            // Agar yozilgan lekin to'lov qilinmagan (Kutilmoqda) bo'lsa
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Kursingiz tasdiqlanmagan yoki to\'lov kutilmoqda.'));
            return $this->redirect(['/payment/create', 'course_id' => $course_id]);
        }

        // Darslarni olish
        $lessons = Lesson::find()->where(['course_id' => $course_id, 'is_published' => 1])->orderBy(['order_number' => SORT_ASC])->all();
        $courseTests = CourseTest::find()->where(['course_id' => $course_id])->with('test')->orderBy(['order_number' => SORT_ASC])->all();

        $progressData = [];
        $lockedStatus = [];
        foreach ($lessons as $lesson) {
            $progressData[$lesson->id] = LessonProgress::findOne(['student_id' => $student->id, 'lesson_id' => $lesson->id]);
            $lockedStatus[$lesson->id] = !$this->canAccessLesson($student->id, $lesson);
        }

        return $this->render('lessons', [
            'lessons' => $lessons,
            'course' => $course, // endi enrollment->course deb o'tirmaymiz
            'progressData' => $progressData,
            'lockedStatus' => $lockedStatus,
            'courseTests' => $courseTests,
            'student' => $student,
        ]);
    }

    public function actionView($id)
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        $lesson = $this->findModel($id);
        $course = $lesson->course;

        $enrollment = Enrollment::findOne(['student_id' => $student->id, 'course_id' => $course->id]);

        // Xavfsizlik: Boshqa birov link orqali to'g'ridan-to'g'ri darsga kirmasligi uchun
        if (!$enrollment) {
            if ($course->isFree()) {
                $enrollment = new Enrollment(['student_id' => $student->id, 'course_id' => $course->id, 'enrolled_on' => date('Y-m-d H:i:s'), 'status' => Enrollment::STATUS_ACTIVE]);
                $enrollment->save(false);
            } else {
                throw new NotFoundHttpException('Access denied. Please enroll first.');
            }
        } elseif ($enrollment->status !== Enrollment::STATUS_ACTIVE) {
            throw new NotFoundHttpException('Access denied. Payment is pending.');
        }

        if (!$this->canAccessLesson($student->id, $lesson)) {
            Yii::$app->session->setFlash('error', 'Complete previous lessons first!');
            return $this->redirect(['course', 'course_id' => $lesson->course_id]);
        }

        $progress = LessonProgress::findOne(['student_id' => $student->id, 'lesson_id' => $lesson->id]);
        if (!$progress) {
            $progress = new LessonProgress(['student_id' => $student->id, 'lesson_id' => $lesson->id, 'status' => 'in_progress']);
            $progress->save();
        }

        return $this->render('view', ['lesson' => $lesson, 'progress' => $progress]);
    }


    public function actionUpdateProgress($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        $lesson = Lesson::findOne($id);

        if (!$student || !$lesson) {
            return ['success' => false, 'error' => 'Invalid request'];
        }

        $progress = LessonProgress::findOne([
            'student_id' => $student->id,
            'lesson_id' => $lesson->id
        ]);

        if (!$progress) {
            $progress = new LessonProgress([
                'student_id' => $student->id,
                'lesson_id' => $lesson->id
            ]);
        }

        $videoProgress = Yii::$app->request->post('video_progress', 0);
        $timeSpent = Yii::$app->request->post('time_spent', 0);

        $progress->video_progress = $videoProgress;
        $progress->time_spent += $timeSpent;
        $progress->status = 'in_progress';

        // Auto-complete if watched enough
        if ($lesson->content_type === 'video' && $lesson->min_watch_time) {
            if ($videoProgress >= $lesson->min_watch_time) {
                $progress->status = 'completed';
                $progress->progress_percentage = 100;
                $progress->completed_at = time();
            }
        }

        $progress->save();

        return ['success' => true, 'progress' => $progress->attributes];
    }

    public function actionComplete($id)
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        $lesson = Lesson::findOne($id);

        $progress = LessonProgress::findOne([
            'student_id' => $student->id,
            'lesson_id' => $lesson->id
        ]);

        if (!$progress) {
            $progress = new LessonProgress([
                'student_id' => $student->id,
                'lesson_id' => $lesson->id
            ]);
        }

        $progress->status = 'completed';
        $progress->progress_percentage = 100;
        $progress->completed_at = time();
        $progress->save();

        Yii::$app->session->setFlash('success', 'Lesson completed!');
        return $this->redirect(['course', 'course_id' => $lesson->course_id]); // ✅ FIXED: 'course' action ga
    }

    protected function canAccessLesson($studentId, $lesson)
    {
        // First lesson always accessible
        if ($lesson->order_number == 1) {
            return true;
        }

        // Check previous lesson completed
        $previousLesson = Lesson::find()
            ->where([
                'course_id' => $lesson->course_id,
                'order_number' => $lesson->order_number - 1,
                'is_published' => 1
            ])
            ->one();

        if (!$previousLesson) {
            return true;
        }

        $previousProgress = LessonProgress::findOne([
            'student_id' => $studentId,
            'lesson_id' => $previousLesson->id,
            'status' => 'completed'
        ]);

        return $previousProgress !== null;
    }

    protected function findModel($id)
    {
        if (($model = Lesson::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Lesson not found.');
    }
}
