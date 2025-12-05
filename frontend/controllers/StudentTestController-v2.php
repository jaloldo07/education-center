<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\Json;
use common\models\Student;
use common\models\Test;
use common\models\TestAttempt;
use common\models\TestQuestion;
use common\models\TestAnswer;
use common\models\Enrollment;

class StudentTestController extends Controller
{
    // ✅ Security constants
    const MAX_FACE_PHOTO_SIZE = 2 * 1024 * 1024; // 2MB
    const ALLOWED_IMAGE_TYPES = ['image/png', 'image/jpeg', 'image/jpg'];
    
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
        try {
            $student = $this->getStudent();

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

            $availableTests = [];
            foreach ($tests as $test) {
                if ($test->isAvailable()) {
                    $availableTests[] = $test;
                }
            }

            $myAttempts = TestAttempt::find()
                ->where(['student_id' => $student->id])
                ->with(['test'])
                ->orderBy(['created_at' => SORT_DESC])
                ->limit(20)
                ->all();

            return $this->render('index', [
                'student' => $student,
                'tests' => $availableTests,
                'myAttempts' => $myAttempts,
            ]);
            
        } catch (\Exception $e) {
            Yii::error('Test index error: ' . $e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Error loading tests.');
            return $this->render('index', [
                'student' => $this->getStudent(),
                'tests' => [],
                'myAttempts' => [],
            ]);
        }
    }

    public function actionStart($id)
    {
        $student = $this->getStudent();
        $test = Test::findOne($id);

        if (!$test || !$test->isAvailable()) {
            throw new NotFoundHttpException('Test not found or not available.');
        }

        if (!$this->checkTestAccess($student, $test)) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
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
            throw new NotFoundHttpException('Test not found or not available.');
        }

        if (!$this->checkTestAccess($student, $test)) {
            Yii::$app->session->setFlash('error', 'You do not have access to this test.');
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
                try {
                    $filename = $this->processAndSaveFacePhoto($faceData, $student->id);
                    $attempt->face_photo = $filename;
                } catch (\Exception $e) {
                    Yii::error('Face photo error: ' . $e->getMessage(), __METHOD__);
                    Yii::$app->session->setFlash('error', 'Invalid face photo: ' . $e->getMessage());
                    return $this->redirect(['start', 'id' => $test->id]);
                }
            }
        }

        try {
            if ($attempt->save()) {
                Yii::$app->session->set('test_attempt_id', $attempt->id);
                return $this->redirect(['take', 'id' => $attempt->id]);
            }
        } catch (\Exception $e) {
            Yii::error('Attempt creation error: ' . $e->getMessage(), __METHOD__);
            Yii::$app->session->setFlash('error', 'Could not start test.');
        }
        
        return $this->redirect(['start', 'id' => $test->id]);
    }

    protected function processAndSaveFacePhoto($faceData, $studentId)
    {
        if (!preg_match('/^data:image\/(png|jpeg|jpg);base64,/', $faceData, $matches)) {
            throw new BadRequestHttpException('Invalid image format.');
        }
        
        $faceData = preg_replace('/^data:image\/(png|jpeg|jpg);base64,/', '', $faceData);
        $imageData = base64_decode($faceData);
        
        if ($imageData === false || strlen($imageData) > self::MAX_FACE_PHOTO_SIZE) {
            throw new BadRequestHttpException('Invalid or too large image.');
        }
        
        $image = @imagecreatefromstring($imageData);
        if (!$image) {
            throw new BadRequestHttpException('Invalid image data.');
        }
        
        $filename = 'face_' . $studentId . '_' . time() . '_' . uniqid() . '.png';
        $uploadPath = Yii::getAlias('@frontend/web/uploads/faces/');
        
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }
        
        imagepng($image, $uploadPath . $filename, 9);
        imagedestroy($image);
        chmod($uploadPath . $filename, 0644);
        
        return $filename;
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

    /**
     * ✅ FIXED: Submit test answers with proper type normalization
     */
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
                $answers = Yii::$app->request->post('answers', []);

                // ✅ Debug: Log received answers
                Yii::info([
                    'attempt_id' => $attempt->id,
                    'received_answers' => $answers
                ], 'test-submit');

                foreach ($answers as $questionId => $answer) {
                    $question = TestQuestion::findOne($questionId);

                    if (!$question || $question->test_id != $attempt->test_id) {
                        continue;
                    }

                    $testAnswer = new TestAnswer();
                    $testAnswer->attempt_id = $attempt->id;
                    $testAnswer->question_id = $questionId;
                    $testAnswer->answered_at = time();

                    // ✅ FIXED: Process answer with proper type normalization
                    if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE) {
                        // Single choice: Convert to string array
                        $testAnswer->answerArray = !empty($answer) ? [strval($answer)] : [];
                        
                    } elseif ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE) {
                        // Multiple choice: Convert all to strings and filter empty
                        if (is_array($answer)) {
                            $testAnswer->answerArray = array_values(array_filter(
                                array_map('strval', $answer),
                                function ($v) {
                                    return $v !== '';
                                }
                            ));
                        } else {
                            $testAnswer->answerArray = [];
                        }
                        
                    } else {
                        // Text: Single string in array
                        $testAnswer->answerArray = !empty($answer) ? [trim($answer)] : [];
                    }
                    
                    // ✅ Debug: Log processed answer
                    Yii::info([
                        'question_id' => $questionId,
                        'question_type' => $question->question_type,
                        'raw_answer' => $answer,
                        'processed_answer' => $testAnswer->answerArray
                    ], 'test-answer-processing');
                    
                    if (!$testAnswer->save()) {
                        $errors = implode(', ', array_map(function($err) {
                            return implode(', ', $err);
                        }, $testAnswer->getErrors()));
                        throw new \Exception('Failed to save answer: ' . $errors);
                    }

                    // Grade the answer
                    $testAnswer->grade();
                }

                // Complete the attempt
                $attempt->complete();
                
                $transaction->commit();

                Yii::info("Test submitted successfully: AttemptID={$attempt->id}", __METHOD__);
                Yii::$app->session->setFlash('success', 'Test submitted successfully!');
                return $this->redirect(['result', 'id' => $attempt->id]);
                
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::error('Test submission error: ' . $e->getMessage() . "\n" . $e->getTraceAsString(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Error submitting test: ' . $e->getMessage());
                return $this->redirect(['take', 'id' => $id]);
            }
        }

        return $this->redirect(['take', 'id' => $id]);
    }

    public function actionResult($id)
    {
        $student = $this->getStudent();
        $attempt = TestAttempt::findOne(['id' => $id, 'student_id' => $student->id]);

        if (!$attempt || $attempt->status !== TestAttempt::STATUS_COMPLETED) {
            throw new NotFoundHttpException('Test result not found.');
        }

        $answers = TestAnswer::find()
            ->where(['attempt_id' => $attempt->id])
            ->with(['question'])
            ->all();

        return $this->render('result', [
            'attempt' => $attempt,
            'answers' => $answers,
        ]);
    }

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
