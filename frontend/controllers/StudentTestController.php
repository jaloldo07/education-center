<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\helpers\Json;
use common\models\Student;
use common\models\Test;
use common\models\TestAttempt;
use common\models\TestQuestion;
use common\models\TestAnswer;
use common\models\Enrollment;
use yii\web\ForbiddenHttpException;
class StudentTestController extends Controller
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
                            return Yii::$app->user->identity->role === 'student';
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Get student profile
     */
    protected function getStudent()
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);

        if (!$student) {
            throw new NotFoundHttpException('Student profile not found.');
        }

        return $student;
    }

    /**
     * List available tests for student
     */
    public function actionIndex()
    {
        $student = $this->getStudent();

        // Get student's groups
        $groupIds = Enrollment::find()
            ->select('group_id')
            ->where(['student_id' => $student->id, 'status' => Enrollment::STATUS_ACTIVE])
            ->column();

        // Get student's courses
        $courseIds = Enrollment::find()
            ->select(['course.id'])
            ->leftJoin('group', 'enrollment.group_id = group.id')
            ->leftJoin('course', 'group.course_id = course.id')
            ->where(['enrollment.student_id' => $student->id])
            ->distinct()
            ->column();

        // Find active tests for student's courses/groups
        $tests = Test::find()
            ->where(['status' => Test::STATUS_ACTIVE])
            ->andWhere([
                'or',
                ['course_id' => $courseIds],
                ['group_id' => $groupIds],
            ])
            ->with(['course', 'group', 'teacher'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        // Filter available tests
        $availableTests = [];
        foreach ($tests as $test) {
            if ($test->isAvailable()) {
                $availableTests[] = $test;
            }
        }

        $attempts = TestAttempt::find()
            ->where(['student_id' => $student->id])
            ->with(['test'])
            ->orderBy(['finished_at' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('index', [
            'student' => $student,
            'tests' => $availableTests,
            'attempts' => $attempts, 
        ]);
    }

    /**
     * Start test (Face control if required)
     */
    public function actionStart($id)
    {
        $student = $this->getStudent();

        $test = Test::findOne($id);

        if (!$test || !$test->isAvailable()) {
            throw new NotFoundHttpException('Test not found or not available.');
        }

        $hasAccess = $this->checkTestAccess($student, $test);

        if (!$hasAccess) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
            return $this->redirect(['index']);
        }

        return $this->render('start', [
            'test' => $test,
            'student' => $student,
        ]);
    }

    /**
     * Upload face photo and begin test
     */
    public function actionBegin($id)
    {
        $student = $this->getStudent();

        $test = Test::findOne($id);

        if (!$test || !$test->isAvailable()) {
            throw new NotFoundHttpException('Test not found or not available.');
        }

        // Check access
        if (!$this->checkTestAccess($student, $test)) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
            return $this->redirect(['index']);
        }

        // Create test attempt
        $attempt = new TestAttempt();
        $attempt->test_id = $test->id;
        $attempt->student_id = $student->id;
        $attempt->started_at = time();
        $attempt->status = TestAttempt::STATUS_IN_PROGRESS;
        $attempt->ip_address = Yii::$app->request->userIP;
        $attempt->user_agent = Yii::$app->request->userAgent;

        // Handle face photo if required
        if ($test->require_face_control && Yii::$app->request->isPost) {
            $faceData = Yii::$app->request->post('face_photo');

            if ($faceData) {
                $faceData = str_replace('data:image/png;base64,', '', $faceData);
                $faceData = str_replace(' ', '+', $faceData);
                $imageData = base64_decode($faceData);

                $filename = 'face_' . $student->id . '_' . time() . '.png';
                $uploadPath = Yii::getAlias('@frontend/web/uploads/faces/');

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                file_put_contents($uploadPath . $filename, $imageData);
                $attempt->face_photo = $filename;
            }
        }

        if ($attempt->save()) {
            Yii::$app->session->set('test_attempt_id', $attempt->id);
            return $this->redirect(['take', 'id' => $attempt->id]);
        }

        Yii::$app->session->setFlash('error', 'Could not start test. Please try again.');
        return $this->redirect(['start', 'id' => $test->id]);
    }

    /**
     * Take test (show questions)
     */
    public function actionTake($id)
    {
        $student = $this->getStudent();

        $attempt = TestAttempt::findOne(['id' => $id, 'student_id' => $student->id]);

        if (!$attempt || $attempt->status !== TestAttempt::STATUS_IN_PROGRESS) {
            throw new NotFoundHttpException('Test attempt not found.');
        }

        $test = $attempt->test;
        $questions = $test->getQuestions()->all();

        // Calculate time remaining
        $timeLimit = $test->duration * 60; 
        $timeElapsed = time() - $attempt->started_at;
        $timeRemaining = max(0, $timeLimit - $timeElapsed);

        return $this->render('take', [
            'attempt' => $attempt,
            'test' => $test,
            'questions' => $questions,
            'timeRemaining' => $timeRemaining,
        ]);
    }

    /**
     * Submit test answers
     */
    public function actionSubmit($id)
    {
        $student = $this->getStudent();

        $attempt = TestAttempt::findOne(['id' => $id, 'student_id' => $student->id]);

        if (!$attempt || $attempt->status !== TestAttempt::STATUS_IN_PROGRESS) {
            throw new NotFoundHttpException('Test attempt not found.');
        }

        if (Yii::$app->request->isPost) {
            $answers = Yii::$app->request->post('answers', []);

            // Save all answers
            foreach ($answers as $questionId => $answer) {
                $question = TestQuestion::findOne($questionId);

                if (!$question || $question->test_id != $attempt->test_id) {
                    continue;
                }

                $testAnswer = new TestAnswer();
                $testAnswer->attempt_id = $attempt->id;
                $testAnswer->question_id = $questionId;
                $testAnswer->answered_at = time();

                // Process answer based on question type
                if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE) {
                    $testAnswer->answerArray = !empty($answer) ? [$answer] : [];
                } elseif ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE) {
                    $testAnswer->answerArray = is_array($answer) ? array_filter($answer, function ($v) {
                        return $v !== '' && $v !== null;
                    }) : [];
                } else { // TEXT
                    $testAnswer->answerArray = !empty($answer) ? [$answer] : [];
                }
                $testAnswer->save();

                $testAnswer->grade();
            }

            $attempt->complete();

            Yii::$app->session->setFlash('success', 'Test submitted successfully!');
            return $this->redirect(['result', 'id' => $attempt->id]);
        }

        return $this->redirect(['take', 'id' => $id]);
    }

    /**
     * View test result
     */
    public function actionResult($id)
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->student) {
            throw new ForbiddenHttpException('Access denied');
        }

        $attempt = TestAttempt::findOne([
            'id' => $id,
            'student_id' => Yii::$app->user->identity->student->id
        ]);

        if (!$attempt) {
            throw new NotFoundHttpException('Test not found');
        }

        $answers = TestAnswer::find()
            ->where(['attempt_id' => $attempt->id])
            ->with(['question'])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return $this->render('result', [
            'attempt' => $attempt,
            'answers' => $answers
        ]);
    }

    /**
     * Check if student has access to test
     */
    protected function checkTestAccess($student, $test)
    {
        $groupIds = Enrollment::find()
            ->select('group_id')
            ->where(['student_id' => $student->id, 'status' => Enrollment::STATUS_ACTIVE])
            ->column();

        $courseIds = Enrollment::find()
            ->select(['course.id'])
            ->leftJoin('group', 'enrollment.group_id = group.id')
            ->leftJoin('course', 'group.course_id = course.id')
            ->where(['enrollment.student_id' => $student->id])
            ->distinct()
            ->column();

        if ($test->group_id && in_array($test->group_id, $groupIds)) {
            return true;
        }

        if ($test->course_id && in_array($test->course_id, $courseIds)) {
            return true;
        }

        return false;
    }
}
