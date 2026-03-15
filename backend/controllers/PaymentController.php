<?php

namespace backend\controllers;

use Yii;
use common\models\Payment;
use common\models\Student;
use common\models\Course;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
{
    /**
     * {@inheritdoc}
     */
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

    /**
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $courseId = Yii::$app->request->get('course_id');

        $query = Payment::find()->with(['student', 'course']);

        // Agar kurs tanlangan bo'lsa - filter qilish
        if ($courseId) {
            $query->where(['course_id' => $courseId]);
        }

        $query->orderBy(['payment_date' => SORT_DESC, 'created_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        // Kurslar ro'yxati (dropdown uchun)
        $courses = ArrayHelper::map(Course::find()->all(), 'id', 'name');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'courses' => $courses,
            'selectedCourse' => $courseId,
        ]);
    }

    /**
     * Displays a single Payment model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();
        $model->payment_date = date('Y-m-d');
        $model->payment_type = Payment::TYPE_MONTHLY;
        $model->status = Payment::STATUS_PAID; // Admin yaratganda to'langan deb hisoblaymiz

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment has been recorded successfully.'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'id', 'full_name'),
            'courses' => ArrayHelper::map(Course::find()->all(), 'id', 'name'),
        ]);
    }

    /**
     * Updates an existing Payment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Payment has been updated successfully.'));
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'students' => ArrayHelper::map(Student::find()->all(), 'id', 'full_name'),
            'courses' => ArrayHelper::map(Course::find()->all(), 'id', 'name'),
        ]);
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Payment has been deleted successfully.'));
        return $this->redirect(['index']);
    }


     /* To'lovni tasdiqlash (Student chek yuborganda)
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        $model->status = \common\models\Payment::STATUS_PAID;
        
        if ($model->save(false)) { 
            
            $enrollment = \common\models\Enrollment::findOne([
                'student_id' => $model->student_id,
                'course_id' => $model->course_id
            ]);

            // 🔥 Agar talabaning arizasi hali bazada umuman yo'q bo'lsa, yangi yaratamiz
            if (!$enrollment) {
                $enrollment = new \common\models\Enrollment();
                $enrollment->student_id = $model->student_id;
                $enrollment->course_id = $model->course_id;
                $enrollment->enrolled_on = date('Y-m-d H:i:s');
            }
            
            // Va uni faol (Active) qilib qo'yamiz
            $enrollment->status = \common\models\Enrollment::STATUS_ACTIVE; 
            $enrollment->save(false);

            Yii::$app->session->setFlash('success', Yii::t('app', 'To\'lov muvaffaqiyatli tasdiqlandi va talabaga kurs ochildi.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Tasdiqlashda xatolik yuz berdi.'));
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * To'lovni bekor qilish (Chek xato bo'lsa)
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);
        $model->status = Payment::STATUS_FAILED;
        
        if ($model->save(false)) {
            Yii::$app->session->setFlash('warning', Yii::t('app', 'To\'lov bekor qilindi.'));
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Karta sozlamalarini yangilash
     */
    public function actionUpdateCards()
    {
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            
            $keys = ['uzcard_number', 'uzcard_name', 'humo_number', 'humo_name'];
            
            foreach ($keys as $key) {
                if (isset($post[$key])) {
                    $setting = \common\models\Setting::findOne(['key' => $key]);
                    if ($setting) {
                        $setting->value = $post[$key];
                        $setting->save(false);
                    }
                }
            }
            
            Yii::$app->session->setFlash('success', Yii::t('app', 'Karta ma\'lumotlari muvaffaqiyatli yangilandi!'));
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}