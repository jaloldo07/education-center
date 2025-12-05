<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Teacher;
use common\models\Course;
use common\models\Group;
use common\models\Test;
use common\models\TestQuestion;
use common\models\TestAttempt;
use common\models\TestAnswer;
use yii\web\NotFoundHttpException;

class TestController extends Controller
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
                            // ✅ Added null check
                            if (Yii::$app->user->isGuest || !Yii::$app->user->identity) {
                                return false;
                            }
                            return Yii::$app->user->identity->role === 'teacher';
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Get teacher profile
     */
    protected function getTeacher()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        if (!$teacher) {
            throw new NotFoundHttpException('Teacher profile not found.');
        }

        return $teacher;
    }

    /**
     * List all tests
     */
    public function actionIndex()
    {
        $teacher = $this->getTeacher();

        $tests = Test::find()
            ->where(['teacher_id' => $teacher->id])
            ->with(['course', 'group'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'teacher' => $teacher,
            'tests' => $tests,
        ]);
    }

    /**
     * Create new test
     */
    public function actionCreate()
    {
        $teacher = $this->getTeacher();

        $model = new Test();
        $model->teacher_id = $teacher->id;
        $model->status = Test::STATUS_DRAFT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Test created successfully. Now add questions.');
            return $this->redirect(['manage-questions', 'id' => $model->id]);
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();
        $groups = Group::find()->where(['teacher_id' => $teacher->id])->all();

        return $this->render('create', [
            'model' => $model,
            'courses' => $courses,
            'groups' => $groups,
        ]);
    }

    /**
     * Update test
     */
    public function actionUpdate($id)
    {
        $teacher = $this->getTeacher();

        $model = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$model) {
            throw new NotFoundHttpException('Test not found.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Test updated successfully.');
            return $this->redirect(['index']);
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();
        $groups = Group::find()->where(['teacher_id' => $teacher->id])->all();

        return $this->render('update', [
            'model' => $model,
            'courses' => $courses,
            'groups' => $groups,
        ]);
    }

    /**
     * View test details
     */
    public function actionView($id)
    {
        $teacher = $this->getTeacher();

        $model = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$model) {
            throw new NotFoundHttpException('Test not found.');
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Delete test
     */
    public function actionDelete($id)
    {
        $teacher = $this->getTeacher();

        $model = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$model) {
            throw new NotFoundHttpException('Test not found.');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Test deleted successfully.');

        return $this->redirect(['index']);
    }

    /**
     * Manage test questions
     */
    public function actionManageQuestions($id)
    {
        $teacher = $this->getTeacher();

        $test = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$test) {
            throw new NotFoundHttpException('Test not found.');
        }

        $questions = TestQuestion::find()
            ->where(['test_id' => $test->id])
            ->orderBy(['order' => SORT_ASC])
            ->all();

        return $this->render('manage-questions', [
            'test' => $test,
            'questions' => $questions,
        ]);
    }

    /**
     * ✅ FIXED: Add question to test with proper type handling
     */
    public function actionAddQuestion($test_id)
    {
        $teacher = $this->getTeacher();

        $test = Test::findOne(['id' => $test_id, 'teacher_id' => $teacher->id]);

        if (!$test) {
            throw new NotFoundHttpException('Test not found.');
        }

        $model = new TestQuestion();
        $model->test_id = $test->id;
        $model->question_type = TestQuestion::TYPE_SINGLE_CHOICE;
        $model->points = 1;

        // Get next order number
        $maxOrder = TestQuestion::find()->where(['test_id' => $test->id])->max('`order`');
        $model->order = $maxOrder !== null ? $maxOrder + 1 : 0;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // Process options and correct answers
            $post = Yii::$app->request->post('TestQuestion', []);

            // ✅ Process options
            if (isset($post['optionsArray'])) {
                $model->optionsArray = array_filter($post['optionsArray'], function ($val) {
                    return !empty(trim($val));
                });
            }

            // ✅ FIXED: Process correct answers with proper type normalization
            if (isset($post['correctAnswerArray'])) {
                if ($model->question_type === TestQuestion::TYPE_TEXT) {
                    // Text answer
                    $model->correctAnswerArray = [trim($post['correctAnswerArray'])];
                } elseif ($model->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE) {
                    // Multiple choice - normalize to string array
                    $answers = is_array($post['correctAnswerArray'])
                        ? $post['correctAnswerArray']
                        : [$post['correctAnswerArray']];

                    // ✅ Convert all to strings and filter empty
                    $model->correctAnswerArray = array_values(array_filter(
                        array_map('strval', $answers),
                        function ($v) {
                            return $v !== '';
                        }
                    ));
                } else {
                    // Single choice - normalize to string array
                    $model->correctAnswerArray = [strval($post['correctAnswerArray'])];
                }
            }

            // ✅ Debug logging
            Yii::info([
                'question_type' => $model->question_type,
                'options' => $model->optionsArray,
                'correct_answer' => $model->correctAnswerArray,
                'post_data' => $post
            ], 'test-question-add');

            if ($model->save()) {
                $test->updateTotalQuestions();
                Yii::$app->session->setFlash('success', 'Question added successfully.');
                return $this->redirect(['manage-questions', 'id' => $test->id]);
            } else {
                // Show validation errors
                $errors = implode(', ', array_map(function ($err) {
                    return implode(', ', $err);
                }, $model->getErrors()));
                Yii::$app->session->setFlash('error', 'Failed to save: ' . $errors);
            }
        }

        return $this->render('add-question', [
            'model' => $model,
            'test' => $test,
        ]);
    }

    /**
     * ✅ FIXED: Edit question with proper type handling
     */
    public function actionEditQuestion($id)
    {
        $teacher = $this->getTeacher();

        $model = TestQuestion::findOne($id);

        if (!$model || $model->test->teacher_id != $teacher->id) {
            throw new NotFoundHttpException('Question not found.');
        }

        $test = $model->test;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            // Process options and correct answers
            $post = Yii::$app->request->post('TestQuestion', []);

            // ✅ Process options
            if (isset($post['optionsArray'])) {
                $model->optionsArray = array_filter($post['optionsArray'], function ($val) {
                    return !empty(trim($val));
                });
            }

            // ✅ FIXED: Process correct answers with proper type normalization
            if (isset($post['correctAnswerArray'])) {
                if ($model->question_type === TestQuestion::TYPE_TEXT) {
                    // Text - single value
                    $model->correctAnswerArray = [trim($post['correctAnswerArray'])];
                } elseif ($model->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE) {
                    // Multiple choice - normalize to string array
                    $answers = is_array($post['correctAnswerArray'])
                        ? $post['correctAnswerArray']
                        : [$post['correctAnswerArray']];

                    // ✅ CRITICAL: Convert to strings and filter empty
                    $model->correctAnswerArray = array_values(array_filter(
                        array_map('strval', $answers),
                        function ($v) {
                            return $v !== '';
                        }
                    ));
                } else {
                    // Single choice - normalize to string array
                    $model->correctAnswerArray = [strval($post['correctAnswerArray'])];
                }
            } else {
                // No answer provided
                $model->correctAnswerArray = [];
            }

            // ✅ Debug logging
            Yii::info([
                'question_id' => $model->id,
                'question_type' => $model->question_type,
                'options' => $model->optionsArray,
                'correct_answer' => $model->correctAnswerArray,
                'post_data' => $post
            ], 'test-question-edit');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Question updated successfully.');
                return $this->redirect(['manage-questions', 'id' => $test->id]);
            } else {
                // Show validation errors
                $errors = implode(', ', array_map(function ($err) {
                    return implode(', ', $err);
                }, $model->getErrors()));
                Yii::$app->session->setFlash('error', 'Failed to update: ' . $errors);
            }
        }

        return $this->render('edit-questions', [
            'model' => $model,
            'test' => $test,
        ]);
    }

    /**
     * Delete question
     */
    public function actionDeleteQuestion($id)
    {
        $teacher = $this->getTeacher();

        $model = TestQuestion::findOne($id);

        if (!$model || $model->test->teacher_id != $teacher->id) {
            throw new NotFoundHttpException('Question not found.');
        }

        $test_id = $model->test_id;
        $model->delete();

        $test = Test::findOne($test_id);
        $test->updateTotalQuestions();

        Yii::$app->session->setFlash('success', 'Question deleted successfully.');

        return $this->redirect(['manage-questions', 'id' => $test_id]);
    }

    /**
     * View test results
     */
    public function actionResults($id)
    {
        $teacher = $this->getTeacher();

        // $id = test ID
        $test = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$test) {
            throw new NotFoundHttpException('Test not found');
        }

        // Get all attempts for this test
        $attempts = TestAttempt::find()
            ->where(['test_id' => $test->id])
            ->with(['student'])
            ->orderBy(['finished_at' => SORT_DESC])
            ->all();

        return $this->render('results', [
            'test' => $test,
            'attempts' => $attempts
        ]);
    }

    /**
     * View student attempt details
     */
    public function actionViewAttempt($id)
    {
        $attempt = TestAttempt::findOne($id);

        if (!$attempt) {
            throw new NotFoundHttpException('Test attempt not found');
        }

        $answers = TestAnswer::find()
            ->where(['attempt_id' => $attempt->id])
            ->with(['question'])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        return $this->render('view-attempt', [
            'attempt' => $attempt,
            'answers' => $answers
        ]);
    }
}
