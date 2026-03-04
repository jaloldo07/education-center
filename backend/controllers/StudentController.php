<?php

namespace backend\controllers;

use Yii;
use common\models\Student;
use common\models\User; // User modelini chaqiramiz
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class StudentController extends Controller
{
    /**
     * @inheritDoc
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
     * Lists all Student models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Student::find()->with('user')->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Student model.
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
     * Creates a new Student model.
     * Admin qo'lda Login va Parol kiritadi.
     */
    public function actionCreate()
    {
        $model = new Student();
        $user = new User();
        $user->scenario = 'create'; // Parol majburiy

        // Standart qiymatlar
        $model->enrolled_date = date('Y-m-d');

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $user->load($this->request->post());

            // Userdagi emailni Studentga o'tkazamiz (agar student email bo'sh bo'lsa)
            if (empty($model->email)) {
                $model->email = $user->email;
            } else {
                // Yoki aksincha, student emailini userga
                $user->email = $model->email;
            }

            // Ikkalasi ham validatsiyadan o'tadimi?
            if ($model->validate() && $user->validate()) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 1. Userni yaratamiz
                    $user->setPassword($user->password);
                    $user->generateAuthKey();
                    $user->role = User::ROLE_STUDENT; // Rol: Student
                    $user->status = User::STATUS_ACTIVE;
                    
                    if (!$user->save(false)) {
                        throw new \Exception('Userni saqlab bo\'lmadi.');
                    }

                    // 2. Studentni Userga bog'laymiz
                    $model->user_id = $user->id;

                    // 3. Studentni saqlaymiz
                    if (!$model->save(false)) {
                        throw new \Exception('Studentni saqlab bo\'lmadi.');
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Talaba va uning logini muvaffaqiyatli yaratildi.');
                    return $this->redirect(['view', 'id' => $model->id]);

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Xatolik yuz berdi: ' . $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $user, // User modelini Viewga yuboramiz
        ]);
    }

    /**
     * Updates an existing Student model.
     * Login va Parolni ham tahrirlash imkoniyati bilan.
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = $model->user; 

        if (!$user) {
            // Agar eski bazada user bo'lmasa, yangi obyekt yaratamiz (xato bermasligi uchun)
            $user = new User(); 
        }

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $user->load($this->request->post());
            
            // Email sinxronizatsiyasi
            $model->email = $user->email;

            // Validatsiya
            if ($model->validate() && $user->validate(['username', 'email'])) {
                // Parol maydoni to'ldirilgan bo'lsa, parolni yangilaymiz
                if (!empty($user->password)) {
                    $user->setPassword($user->password);
                }
                
                $user->save(false);
                $model->save(false);

                Yii::$app->session->setFlash('success', 'Talaba ma\'lumotlari yangilandi.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing Student model and associated User.
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Userni ham o'chirish kerakmi? Ha, odatda shunday qilinadi.
            if ($model->user) {
                $model->user->delete();
            }
            
            $model->delete();
            $transaction->commit();
            Yii::$app->session->setFlash('success', 'Talaba va uning akkaunti o\'chirildi.');
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'O\'chirishda xatolik: ' . $e->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Student model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Student the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Student::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Sahifa topilmadi.');
    }
}