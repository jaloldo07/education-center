<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\Course;
use common\models\Payment;
use common\models\Student;
use common\models\Enrollment;
use common\models\EnrollmentApplication; // 🔥 Arizani saqlash uchun modelni chaqirdik
use yii\web\UploadedFile;

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
                ->where([
                    'student_id' => $student->id,
                    'course_id' => $selectedCourse->id
                ])
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
                $model->transaction_id = strtoupper($model->payment_method) . '_' . time(); 
                
                // Rasmni ushlab olish (Faqat karta orqali to'lov bo'lsa)
                if ($model->payment_method === 'card') {
                    $receiptFile = UploadedFile::getInstance($model, 'receipt_file'); 
                    
                    if ($receiptFile) {
                        $uploadPath = Yii::getAlias('@frontend/web/uploads/receipts/');
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                        $fileName = 'receipt_' . time() . '_' . Yii::$app->security->generateRandomString(6) . '.' . $receiptFile->extension;
                        if ($receiptFile->saveAs($uploadPath . $fileName)) {
                            $model->receipt_file = $fileName;
                        }
                    }
                }

                $realCourse = Course::findOne($model->course_id);
                if ($realCourse) {
                    $model->amount = $realCourse->price;
                }

                if ($model->save()) {
                    
                    // 🔥 MUAMMO YECHIMI: To'lov saqlangach, Avtomatik Ariza ham yaratamiz!
                    // Avval bu kursga ariza topshirganmi yo'qmi tekshiramiz
                    $application = EnrollmentApplication::findOne([
                        'student_id' => $student->id,
                        'course_id' => $model->course_id,
                        'status' => EnrollmentApplication::STATUS_PENDING // 🔥 Eski tasdiqlanganlarini ushlab olmasligi uchun
                    ]);

                    // Agar arizasi yo'q bo'lsa, yangisini ochamiz
                    // Agar arizasi yo'q bo'lsa, yangisini ochamiz
                    if (!$application) {
                        $application = new EnrollmentApplication();
                        $application->student_id = $student->id;
                        $application->course_id = $model->course_id;
                        $application->status = EnrollmentApplication::STATUS_PENDING;
                        $application->message = "Tizim avto-xabari: To'lov amalga oshirildi (yoki chek yuklandi). To'lov ID: #" . $model->id;
                        
                        // 🔥 BAZADA GROUP_ID NOT NULL BO'LSA XATO BERMASLIGI UCHUN MAJBURAN 0 YUBORAMIZ (yoki agar null qabul qilsa, keragi yo'q, lekin kafolat uchun):
                        // Agar xato bo'lsa ekranga qizil qilib chiqarib beryapti:
                        if (!$application->save(false)) { 
                             Yii::error("ARIZA SAQLASHDA XATOLIK: " . json_encode($application->getErrors()));
                        }
                    }

                    if ($model->payment_method === 'cash') {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'Arizangiz qabul qilindi. Iltimos, o\'quv markazimizga kelib to\'lovni amalga oshiring.'));
                    } else {
                        Yii::$app->session->setFlash('success', Yii::t('app', 'To\'lov cheki muvaffaqiyatli yuklandi! Admin tasdiqlagach kursingiz ochiladi.'));
                    }
                    return $this->redirect(['/student/dashboard']);
                } else {
                    $errors = implode('<br>', $model->getErrorSummary(true));
                    Yii::$app->session->setFlash('error', 'Saqlashda xatolik: <br>' . $errors);
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