<?php

namespace frontend\controllers;

use Yii;
use common\models\Lesson;
use common\models\Course;
use common\models\Teacher;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use common\models\Test;
use common\models\CourseTest;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

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

    protected function getTeacher()
    {
        return Teacher::findOne(['user_id' => Yii::$app->user->id]);
    }

    public function actionIndex()
    {
        $teacher = $this->getTeacher();

        if (!$teacher) {
            Yii::$app->session->setFlash('error', 'You are not a teacher.');
            return $this->redirect(['/site/index']);
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();

        $lessons = Lesson::find()
            ->where(['course_id' => ArrayHelper::getColumn($courses, 'id')])
            ->orderBy(['course_id' => SORT_ASC, 'order_number' => SORT_ASC])
            ->all();

        return $this->render('index', [
            'lessons' => $lessons,
            'courses' => $courses,
        ]);
    }

    public function actionCreate()
    {
        $teacher = $this->getTeacher();
        if (!$teacher) {
            return $this->redirect(['/site/index']);
        }

        $model = new Lesson();

        if ($model->load(Yii::$app->request->post())) {
            $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
            if (!$course) {
                Yii::$app->session->setFlash('error', 'Invalid course.');
                return $this->redirect(['index']);
            }

            // 🔥 Barcha fayl turlari uchun (Video, PDF, Rasm) yuklash mantiqiy to'g'rilandi
            $uploadedFile = UploadedFile::getInstanceByName('Lesson[file]');
            if ($uploadedFile && $uploadedFile->error === UPLOAD_ERR_OK) {
                $extension = strtolower($uploadedFile->extension);

                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'mp4', 'webm', 'ogg'];
                if (in_array($extension, $allowedExtensions)) {
                    $folder = in_array($extension, ['mp4', 'webm', 'ogg']) ? 'videos' : 'lessons';
                    
                    // 🔥 @frontend/web o'rniga @webroot ishlatildi
                    $uploadPath = Yii::getAlias('@webroot/uploads/' . $folder . '/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = $uploadPath . $fileName;

                    if ($uploadedFile->saveAs($destinationPath)) {
                        if (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                            $model->video_url = '/uploads/videos/' . $fileName;
                        } else {
                            $model->file_path = '/uploads/lessons/' . $fileName;
                        }
                    }
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Lesson created!');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to save lesson.');
            }
        }

        $courses = Course::find()->where(['teacher_id' => $teacher->id])->all();
        return $this->render('create', ['model' => $model, 'courses' => $courses]);
    }

    public function actionUpdate($id)
    {
        $teacher = $this->getTeacher();
        $model = $this->findModel($id);

        $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        $oldFile = $model->file_path;
        $oldVideo = $model->video_url;

        if ($model->load(Yii::$app->request->post())) {
            
            $uploadedFile = UploadedFile::getInstanceByName('Lesson[file]');
            if ($uploadedFile && $uploadedFile->error === UPLOAD_ERR_OK) {
                $extension = strtolower($uploadedFile->extension);

                $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'webp', 'mp4', 'webm', 'ogg'];
                if (in_array($extension, $allowedExtensions)) {
                    $folder = in_array($extension, ['mp4', 'webm', 'ogg']) ? 'videos' : 'lessons';
                    
                    // 🔥 @frontend/web o'rniga @webroot
                    $uploadPath = Yii::getAlias('@webroot/uploads/' . $folder . '/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }

                    $fileName = time() . '_' . uniqid() . '.' . $extension;
                    $destinationPath = $uploadPath . $fileName;

                    if ($uploadedFile->saveAs($destinationPath)) {
                        // Eski faylni o'chirish
                        if ($oldFile && file_exists(Yii::getAlias('@webroot') . $oldFile)) {
                            unlink(Yii::getAlias('@webroot') . $oldFile);
                        }
                        if ($oldVideo && strpos($oldVideo, 'youtube.com') === false && file_exists(Yii::getAlias('@webroot') . $oldVideo)) {
                            unlink(Yii::getAlias('@webroot') . $oldVideo);
                        }

                        if (in_array($extension, ['mp4', 'webm', 'ogg'])) {
                            $model->video_url = '/uploads/videos/' . $fileName;
                            $model->file_path = null;
                        } else {
                            $model->file_path = '/uploads/lessons/' . $fileName;
                            $model->video_url = null;
                        }
                    }
                }
            } else {
                $model->file_path = $oldFile;
                $model->video_url = $oldVideo;
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
        $teacher = $this->getTeacher();
        $model = $this->findModel($id);

        $course = Course::findOne(['id' => $model->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) {
            throw new NotFoundHttpException('Lesson not found.');
        }

        if ($model->file_path && file_exists(Yii::getAlias('@webroot') . $model->file_path)) {
            unlink(Yii::getAlias('@webroot') . $model->file_path);
        }
        if ($model->video_url && strpos($model->video_url, 'youtube.com') === false && file_exists(Yii::getAlias('@webroot') . $model->video_url)) {
            unlink(Yii::getAlias('@webroot') . $model->video_url);
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

    // Link test qismlari o'zgarishsiz qoldirildi...
    public function actionLinkTest($course_id)
    {
        $teacher = $this->getTeacher();
        $course = Course::findOne(['id' => $course_id, 'teacher_id' => $teacher->id]);
        if (!$course) throw new NotFoundHttpException();

        $model = new CourseTest();
        $model->course_id = $course_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Test linked!');
            return $this->redirect(['index']);
        }

        $tests = Test::find()->where(['teacher_id' => $teacher->id])->all();
        $linkedTests = CourseTest::find()->where(['course_id' => $course_id])->with('test')->all();

        return $this->render('link-test', [
            'model' => $model,
            'course' => $course,
            'tests' => $tests,
            'linkedTests' => $linkedTests,
        ]);
    }

    public function actionUnlinkTest($id)
    {
        $teacher = $this->getTeacher();
        $courseTest = CourseTest::findOne($id);
        if (!$courseTest) throw new NotFoundHttpException('Test link not found.');

        $course = Course::findOne(['id' => $courseTest->course_id, 'teacher_id' => $teacher->id]);
        if (!$course) throw new NotFoundHttpException('Access denied.');

        $courseId = $courseTest->course_id;
        $courseTest->delete();

        Yii::$app->session->setFlash('success', 'Test unlinked!');
        return $this->redirect(['link-test', 'course_id' => $courseId]);
    }
}