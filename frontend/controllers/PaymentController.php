<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Course;
use common\models\Payment;
use common\models\Student;
use common\models\Enrollment;
use yii\web\UploadedFile; // 🔥 Rasmni yuklash uchun kerak

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
                            return Yii::$app->user->identity->role === 'student';
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Create manual payment (Upload receipt).
     * @param int|null $course_id
     * @return mixed
     */
    public function actionCreate($course_id = null)
    {
        $user_id = Yii::$app->user->id;
        $student = Student::findOne(['user_id' => $user_id]);

        if (!$student) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Student profile not found.'));
            return $this->redirect(['/site/index']);
        }

        $courses = Course::find()->all();
        $selectedCourse = $course_id ? Course::findOne($course_id) : null;
        
        $enrollment = null;
        if ($selectedCourse) {
            $enrollment = Enrollment::find()
                ->alias('e')
                ->joinWith('group g')
                ->where(['e.student_id' => $student->id])
                ->andWhere(['g.course_id' => $selectedCourse->id])
                ->one();
        }

        $model = new Payment();

        if ($selectedCourse) {
            $model->course_id = $selectedCourse->id;
            $model->amount = $selectedCourse->price;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                
                $model->student_id = $student->id;
                $model->payment_method = 'manual_transfer'; // Endi manual
                $model->transaction_id = 'MANUAL_' . time(); 
                
                // 🔥 Rasmni ushlab olish
                $receiptFile = UploadedFile::getInstance($model, 'receipt_image');
                
                if ($receiptFile) {
                    $uploadPath = Yii::getAlias('@frontend/web/uploads/receipts/');
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    $fileName = 'receipt_' . time() . '_' . Yii::$app->security->generateRandomString(6) . '.' . $receiptFile->extension;
                    if ($receiptFile->saveAs($uploadPath . $fileName)) {
                        $model->receipt_image = $fileName;
                    }
                }

                // Kurs narxini bazadan qayta tekshiramiz
                $realCourse = Course::findOne($model->course_id);
                if ($realCourse) {
                    if ($model->payment_type === Payment::TYPE_MONTHLY && $model->amount < $realCourse->price) {
                         $model->amount = $realCourse->price;
                    }
                }

                // To'lovni saqlaymiz (Lekin Enrollmentni darhol Aktiv qilmaymiz!)
                // Admin Paymentni ko'rib tasdiqlaganidan keyingina Enrollment aktiv bo'lishi kerak.
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'To\'lov cheki muvaffaqiyatli yuklandi! Admin tasdiqlagach kursingiz ochiladi.'));
                    return $this->redirect(['/student/dashboard']);
                } else {
                    Yii::$app->session->setFlash('error', Yii::t('app', 'Error saving payment. Please try again.'));
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'courses' => $courses,
            'selectedCourse' => $selectedCourse,
            'enrollment' => $enrollment,
        ]);
    }
}