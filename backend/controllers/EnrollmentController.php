<?php

namespace backend\controllers;

use Yii;
use common\models\Enrollment;
use common\models\Student;
use common\models\Course; // 🔥 Group o'rniga Course chaqirildi
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class EnrollmentController extends Controller
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
                            $role = Yii::$app->user->identity->role;
                            return in_array($role, ['director', 'admin']);
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            // 🔥 'group' o'rniga 'course' ga o'zgardi
            'query' => Enrollment::find()->with(['student', 'course'])->orderBy(['enrolled_on' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Enrollment();
        $model->enrolled_on = date('Y-m-d');
        $model->status = Enrollment::STATUS_ACTIVE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Enrollment has been created successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'id', 'full_name'),
            // 🔥 courses o'zgaruvchisi orqali yuboramiz
            'courses' => ArrayHelper::map(Course::find()->all(), 'id', 'name'),
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Enrollment has been updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'id', 'full_name'),
            // 🔥 courses o'zgaruvchisi orqali yuboramiz
            'courses' => ArrayHelper::map(Course::find()->all(), 'id', 'name'),
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', 'Enrollment has been deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Enrollment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}