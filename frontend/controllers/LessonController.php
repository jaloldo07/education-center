<?php

namespace frontend\controllers;

use Yii;
use common\models\Lesson;
use common\models\Course;
use common\models\Teacher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use common\models\Test;
use common\models\CourseTest;

class LessonController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'roles' => ['@']],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        if (!$teacher) {
            Yii::$app->session->setFlash('error', 'You are not a teacher.');
            return $this->redirect(['/site/index']);
        }

        // Teacher's courses
        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();

        // Get lessons for teacher's courses
        $lessons = Lesson::find()
            ->where(['IN', 'course_id', array_map(function ($c) {
                return $c->id;
            }, $courses)])
            ->orderBy(['course_id' => SORT_ASC, 'order_number' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'lessons' => $lessons,
            'courses' => $courses,
        ]);
    }

    public function actionCreate()
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        if (!$teacher) {
            return $this->redirect(['/site/index']);
        }

        $model = new Lesson();

        if ($model->load(Yii::$app->request->post())) {
            // Verify teacher owns this course
            $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
            if (!$course) {
                Yii::$app->session->setFlash('error', 'Invalid course.');
                return $this->redirect(['index']);
            }

            // ✅ FIX: Manual file handling (bypass temp folder issue)
            // Video URL uchun ham file upload qilish
            if ($model->content_type === 'video' && isset($_FILES['Lesson']['tmp_name']['file']) && $_FILES['Lesson']['error']['file'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['Lesson']['tmp_name']['file'];
                $originalName = $_FILES['Lesson']['name']['file'];
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                // Validate video
                if (in_array(strtolower($extension), ['mp4', 'webm', 'ogg'])) {
                    $uploadPath = Yii::getAlias('@frontend/web/uploads/videos/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = $uploadPath . $fileName;

                    if (move_uploaded_file($tmpName, $destinationPath)) {
                        $model->video_url = '/uploads/videos/' . $fileName; // ✅ Web path
                    }
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Lesson created!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save lesson: ' . json_encode($model->errors));
            }
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();
        return $this->render('create', ['model' => $model, 'courses' => $courses]);
    }

    public function actionUpdate($id)
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        $model = $this->findModel($id);

        $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        $oldFile = $model->file_path;

        if ($model->load(Yii::$app->request->post())) {
            // ✅ FIX: Manual file handling
            if (isset($_FILES['Lesson']['tmp_name']['file']) && $_FILES['Lesson']['error']['file'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['Lesson']['tmp_name']['file'];
                $originalName = $_FILES['Lesson']['name']['file'];
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);

                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'mp4'];
                if (in_array(strtolower($extension), $allowedExtensions)) {
                    $uploadPath = Yii::getAlias('@frontend/web/uploads/lessons/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = $uploadPath . $fileName;

                    if (move_uploaded_file($tmpName, $destinationPath)) {
                        // Delete old file
                        if ($oldFile && file_exists(Yii::getAlias('@frontend/web') . $oldFile)) {
                            unlink(Yii::getAlias('@frontend/web') . $oldFile);
                        }
                        $model->file_path = '/uploads/lessons/' . $fileName;
                    }
                }
            } else {
                $model->file_path = $oldFile;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Lesson updated!');
                return $this->redirect(['index']);
            }
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();
        return $this->render('update', ['model' => $model, 'courses' => $courses]);
    }

    public function actionDelete($id)
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        $model = $this->findModel($id);

        $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        if ($model->file_path && file_exists(Yii::getAlias('@frontend/web') . $model->file_path)) {
            unlink(Yii::getAlias('@frontend/web') . $model->file_path);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Lesson deleted!');

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Lesson::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('Lesson not found.');
    }

    public function actionLinkTest($course_id)
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        $course = Course::findOne(['id' => $course_id, 'teacher_id' => $teacher->id]);

        if (!$course) {
            throw new NotFoundHttpException();
        }

        $model = new CourseTest();
        $model->course_id = $course_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Test linked!');
            return $this->redirect(['index']);
        }

        // Get available tests
        $tests = Test::find()
            ->where(['teacher_id' => $teacher->id])
            ->all();

        // Existing linked tests
        $linkedTests = CourseTest::find()
            ->where(['course_id' => $course_id])
            ->with('test')
            ->all();

        return $this->render('link-test', [
            'model' => $model,
            'course' => $course,
            'tests' => $tests,
            'linkedTests' => $linkedTests,
        ]);
    }
    public function actionUnlinkTest($id)
    {
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);
        $courseTest = CourseTest::findOne($id);

        if (!$courseTest) {
            throw new NotFoundHttpException('Test link not found.');
        }

        // Verify ownership
        $course = Course::findOne(['id' => $courseTest->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) {
            throw new NotFoundHttpException('Access denied.');
        }

        $courseId = $courseTest->course_id;
        $courseTest->delete();

        Yii::$app->session->setFlash('success', 'Test unlinked!');
        return $this->redirect(['link-test', 'course_id' => $courseId]);
    }
}
