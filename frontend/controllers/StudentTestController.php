<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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

    protected function getStudent()
    {
        $student = Student::findOne(['user_id' => Yii::$app->user->id]);
        if (!$student) {
            throw new NotFoundHttpException('Student profile not found.');
        }
        return $student;
    }

    public function actionIndex()
    {
        $student = $this->getStudent();

        // 🔥 Guruhlar orqali emas, to'g'ridan-to'g'ri kurslarni olamiz
        $myCourseIds = Enrollment::find()
            ->select('course_id')
            ->where(['student_id' => $student->id, 'status' => Enrollment::STATUS_ACTIVE])
            ->column();

        // 🔥 Faqat talaba o'qiyotgan kurslardagi testlarni topamiz (group_id tekshiruvi o'chirildi)
        $tests = Test::find()
            ->where(['status' => Test::STATUS_ACTIVE])
            ->andWhere(['course_id' => $myCourseIds])
            ->with(['course', 'teacher']) // group olib tashlandi
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

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

    public function actionStart($id)
    {
        $student = $this->getStudent();
        $test = Test::findOne($id);

        if (!$test || !$test->isAvailable()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Ushbu testning vaqti tugagan yoki hozirda yopiq.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        if (!$this->checkTestAccess($student, $test)) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
            return $this->redirect(['index']);
        }

        $attemptCount = TestAttempt::find()
            ->where(['test_id' => $test->id, 'student_id' => $student->id])
            ->count();

        if ($test->max_attempts > 0 && $attemptCount >= $test->max_attempts) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Siz ushbu test uchun ajratilgan barcha urinishlardan foydalanib bo\'ldingiz.'));
            return $this->redirect(['index']);
        }

        return $this->render('start', [
            'test' => $test,
            'student' => $student,
        ]);
    }

    public function actionBegin($id)
    {
        $student = $this->getStudent();
        $test = Test::findOne($id);

        if (!$test || !$test->isAvailable()) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'Ushbu testning vaqti tugagan yoki hozirda yopiq.'));
            return $this->redirect(Yii::$app->request->referrer ?: ['index']);
        }

        if (!$this->checkTestAccess($student, $test)) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
            return $this->redirect(['index']);
        }

        $attemptCount = TestAttempt::find()
            ->where(['test_id' => $test->id, 'student_id' => $student->id])
            ->count();

        if ($test->max_attempts > 0 && $attemptCount >= $test->max_attempts) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Siz ushbu test uchun ajratilgan barcha urinishlardan foydalanib bo\'ldingiz.'));
            return $this->redirect(['index']);
        }

        $attempt = new TestAttempt();
        $attempt->test_id = $test->id;
        $attempt->student_id = $student->id;
        $attempt->started_at = time();
        $attempt->status = TestAttempt::STATUS_IN_PROGRESS;
        $attempt->ip_address = Yii::$app->request->userIP;
        $attempt->user_agent = Yii::$app->request->userAgent;

        if ($test->require_face_control && Yii::$app->request->isPost) {
            $faceData = Yii::$app->request->post('face_photo');
            if ($faceData) {
                // 🔥 YECHIM: Rasm formati qanaqa bo'lishidan qat'i nazar, verguldan keyingi haqiqiy kodni ajratib olamiz
                $parts = explode(',', $faceData);
                $base64String = end($parts);
                $base64String = str_replace(' ', '+', $base64String);
                $imageData = base64_decode($base64String);
                
                $filename = 'face_' . $student->id . '_' . time() . '.png';
                $uploadPath = Yii::getAlias('@frontend/web/uploads/faces/');
                if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);
                
                file_put_contents($uploadPath . $filename, $imageData);
                $attempt->face_photo = $filename;
            }
        }

        if ($attempt->save()) {
            Yii::$app->session->set('test_attempt_id', $attempt->id);
            return $this->redirect(['take', 'id' => $attempt->id]);
        }

        Yii::$app->session->setFlash('error', 'Could not start test.');
        return $this->redirect(['start', 'id' => $test->id]);
    }

    public function actionTake($id)
    {
        $student = $this->getStudent();
        $attempt = TestAttempt::findOne(['id' => $id, 'student_id' => $student->id]);

        if (!$attempt || $attempt->status !== TestAttempt::STATUS_IN_PROGRESS) {
            throw new NotFoundHttpException('Test attempt not found.');
        }

        $test = $attempt->test;
        $questions = $test->getQuestions()->all();

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

    private function normalizeAnswer($val) {
        if ($val === null) return '';
        $val = trim(strval($val));
        $val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
        $val = str_replace(["‘", "’", "`", "´", "ʼ", "ʻ"], "'", $val);
        return mb_strtolower($val, 'UTF-8');
    }

    public function actionSubmit($id)
    {
        $student = $this->getStudent();
        $attempt = TestAttempt::findOne(['id' => $id, 'student_id' => $student->id]);

        if (!$attempt || $attempt->status !== TestAttempt::STATUS_IN_PROGRESS) {
            throw new NotFoundHttpException('Test attempt not found.');
        }

        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $postAnswers = Yii::$app->request->post('answers', []);

                foreach ($postAnswers as $questionId => $rawValue) {
                    $question = TestQuestion::findOne($questionId);

                    if (!$question || $question->test_id != $attempt->test_id) {
                        continue;
                    }

                    $testAnswer = new TestAnswer();
                    $testAnswer->attempt_id = $attempt->id;
                    $testAnswer->question_id = $questionId;
                    $testAnswer->answered_at = time();

                    $userAnswerArray = [];
                    if (is_array($rawValue)) {
                        $userAnswerArray = array_map('strval', $rawValue);
                        $userAnswerArray = array_filter($userAnswerArray, function($v) { return $v !== ''; });
                        $userAnswerArray = array_values($userAnswerArray);
                    } elseif ($rawValue !== null && $rawValue !== '') {
                        $userAnswerArray = [strval($rawValue)];
                    }

                    $testAnswer->answerArray = $userAnswerArray;
                    $testAnswer->answer = json_encode($userAnswerArray);

                    $correctAnswerArray = [];
                    if (!empty($question->correct_answer)) {
                        $decoded = json_decode($question->correct_answer, true);
                        if (is_array($decoded)) {
                            $correctAnswerArray = array_map('strval', $decoded);
                        } elseif ($decoded !== null && $decoded !== '') {
                            $correctAnswerArray = [strval($decoded)];
                        }
                    }

                    $isCorrect = false;

                    if ($question->question_type == 'text' || $question->question_type == \common\models\TestQuestion::TYPE_TEXT) { 
                        
                        $uText = $this->normalizeAnswer($userAnswerArray[0] ?? '');
                        $cText = $this->normalizeAnswer($correctAnswerArray[0] ?? '');
                        
                        $isCorrect = ($uText === $cText && $uText !== '');

                    } else {
                        if (!empty($userAnswerArray) && !empty($correctAnswerArray)) {
                            $diff1 = array_diff($userAnswerArray, $correctAnswerArray);
                            $diff2 = array_diff($correctAnswerArray, $userAnswerArray);
                            $isCorrect = empty($diff1) && empty($diff2);
                        }
                    }

                    $testAnswer->is_correct = $isCorrect;
                    $testAnswer->points_awarded = $isCorrect ? $question->points : 0;

                    if (!$testAnswer->save(false)) {
                        throw new \Exception('Answer save failed for QID: ' . $questionId);
                    }
                }

                $attempt->complete(); 
                $transaction->commit();

                Yii::$app->session->setFlash('success', 'Test submitted successfully!');
                return $this->redirect(['result', 'id' => $attempt->id]);

            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage()); 
                Yii::$app->session->setFlash('error', 'Error submitting test: ' . $e->getMessage());
                return $this->redirect(['take', 'id' => $id]);
            }
        }

        return $this->redirect(['take', 'id' => $id]);
    }

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

    protected function checkTestAccess($student, $test)
    {
        // 🔥 Xavfsizlik qismi ham tozalandi. Faqat kurs tekshiriladi
        $myCourseIds = Enrollment::find()
            ->select('course_id')
            ->where(['student_id' => $student->id, 'status' => Enrollment::STATUS_ACTIVE])
            ->column();

        if (!in_array($test->course_id, $myCourseIds)) {
            return false;
        }

        return true;
    }
}