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

    protected function getTeacher()
    {
        $teacher = Teacher::findOne(['email' => Yii::$app->user->identity->email]);

        if (!$teacher) {
            throw new NotFoundHttpException('Teacher profile not found.');
        }

        return $teacher;
    }

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

    // SAVOLLARNI TOZALASH UCHUN YORDAMCHI FUNKSIYA
    private function cleanAnswerString($val) {
        if ($val === null) return '';
        $val = trim(strval($val));
        // HTML kodlarni oddiy belgilarga o'tkazamiz
        $val = html_entity_decode($val, ENT_QUOTES, 'UTF-8');
        // O'zbek tilidagi barcha xil tutuq belgilarni bitta qolipga (') tushiramiz
        $val = str_replace(["‘", "’", "`", "´", "ʼ", "ʻ"], "'", $val);
        // Matnni kichik harflarga o'tkazamiz (solishtirishda qulay bo'lishi uchun)
        return mb_strtolower($val, 'UTF-8');
    }

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

        $maxOrder = TestQuestion::find()->where(['test_id' => $test->id])->max('`order`');
        $model->order = $maxOrder !== null ? $maxOrder + 1 : 0;

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());

            $post = Yii::$app->request->post('TestQuestion', []);

            if (isset($post['optionsArray'])) {
                $model->optionsArray = array_filter($post['optionsArray'], function ($val) {
                    return !empty(trim($val));
                });
            }

            if (isset($post['correctAnswerArray'])) {
                if ($model->question_type === TestQuestion::TYPE_TEXT || $model->question_type == 'text') {
                    // MATNLI JAVOBLAR UCHUN: Matnni saqlashdan oldin "yuvib, tozalab" olamiz
                    $cleanedAnswer = $this->cleanAnswerString($post['correctAnswerArray']);
                    $model->correctAnswerArray = [$cleanedAnswer];
                } elseif ($model->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE || $model->question_type == 'multiple_choice') {
                    $answers = is_array($post['correctAnswerArray']) ? $post['correctAnswerArray'] : [$post['correctAnswerArray']];
                    $model->correctAnswerArray = array_values(array_filter(
                        array_map('strval', $answers),
                        function ($v) { return $v !== ''; }
                    ));
                } else {
                    $model->correctAnswerArray = [strval($post['correctAnswerArray'])];
                }
            }

            if ($model->save()) {
                $test->updateTotalQuestions();
                Yii::$app->session->setFlash('success', 'Question added successfully.');
                return $this->redirect(['manage-questions', 'id' => $test->id]);
            } else {
                $errors = implode(', ', array_map(function ($err) { return implode(', ', $err); }, $model->getErrors()));
                Yii::$app->session->setFlash('error', 'Failed to save: ' . $errors);
            }
        }

        return $this->render('add-question', [
            'model' => $model,
            'test' => $test,
        ]);
    }

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

            $post = Yii::$app->request->post('TestQuestion', []);

            if (isset($post['optionsArray'])) {
                $model->optionsArray = array_filter($post['optionsArray'], function ($val) {
                    return !empty(trim($val));
                });
            }

            if (isset($post['correctAnswerArray'])) {
                if ($model->question_type === TestQuestion::TYPE_TEXT || $model->question_type == 'text') {
                    // MATNLI JAVOBLAR UCHUN: Tahrirlanganda ham matn tozalanadi
                    $cleanedAnswer = $this->cleanAnswerString($post['correctAnswerArray']);
                    $model->correctAnswerArray = [$cleanedAnswer];
                } elseif ($model->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE || $model->question_type == 'multiple_choice') {
                    $answers = is_array($post['correctAnswerArray']) ? $post['correctAnswerArray'] : [$post['correctAnswerArray']];
                    $model->correctAnswerArray = array_values(array_filter(
                        array_map('strval', $answers),
                        function ($v) { return $v !== ''; }
                    ));
                } else {
                    $model->correctAnswerArray = [strval($post['correctAnswerArray'])];
                }
            } else {
                $model->correctAnswerArray = [];
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Question updated successfully.');
                return $this->redirect(['manage-questions', 'id' => $test->id]);
            } else {
                $errors = implode(', ', array_map(function ($err) { return implode(', ', $err); }, $model->getErrors()));
                Yii::$app->session->setFlash('error', 'Failed to update: ' . $errors);
            }
        }

        return $this->render('edit-questions', [
            'model' => $model,
            'test' => $test,
        ]);
    }

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

    public function actionResults($id)
    {
        $teacher = $this->getTeacher();

        $test = Test::findOne(['id' => $id, 'teacher_id' => $teacher->id]);

        if (!$test) {
            throw new NotFoundHttpException('Test not found');
        }

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