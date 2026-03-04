<?php

namespace backend\controllers;

use Yii;
use common\models\Teacher;
use common\models\User; // User modelini chaqiramiz
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class TeacherController extends Controller
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
            'query' => Teacher::find()->orderBy(['created_at' => SORT_DESC]),
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

    /**
     * 🔥 YANGILANGAN CREATE: User + Teacher yaratish
     */
    public function actionCreate()
    {
        $model = new Teacher();
        $user = new User();
        $user->scenario = 'create';

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $user->load($this->request->post());

            // 🔥 TUZATISH: Userdagi emailni Teacherga o'zlashtiramiz
            // Shunda admin 2 marta email yozishi shart emas
            $model->email = $user->email;

            // Endi validatsiya qilamiz
            $isValidModel = $model->validate();
            $isValidUser = $user->validate();

            if ($isValidModel && $isValidUser) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 1. Userni saqlash
                    $user->setPassword($user->password);
                    $user->generateAuthKey();
                    $user->role = User::ROLE_TEACHER;
                    $user->status = User::STATUS_ACTIVE;
                    
                    if (!$user->save(false)) {
                        throw new \Exception('Userni saqlab bo\'lmadi.');
                    }

                    // 2. Teacherni bog'lash va saqlash
                    $model->user_id = $user->id;
                    
                    if (!$model->save(false)) {
                        throw new \Exception('Teacherni saqlab bo\'lmadi.');
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Teacher muvaffaqiyatli qo\'shildi.');
                    return $this->redirect(['view', 'id' => $model->id]);

                } catch (\Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', 'Xatolik: ' . $e->getMessage());
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * YANGILANGAN UPDATE: User ma'lumotlarini ham tahrirlash (agar kerak bo'lsa)
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = $model->user; // Bog'langan Userni olamiz

        if (!$user) {
            // Agar eski ma'lumotlarda User yo'q bo'lsa, xatolik chiqmasligi uchun
            $user = new User(); 
        }

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $user->load($this->request->post());

            if ($model->validate() && $user->validate(['username', 'email'])) {
                // Parol o'zgargan bo'lsa yangilaymiz
                if (!empty($user->password)) {
                    $user->setPassword($user->password);
                }
                
                $user->save(false);
                $model->save(false);

                Yii::$app->session->setFlash('success', 'Teacher updated successfully.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Agar User bog'langan bo'lsa, uni ham o'chiramiz (yoki o'chirmaslik - biznes mantiqqa bog'liq)
        // Hozircha Userni ham o'chiramiz:
        if ($model->user) {
            $model->user->delete();
        }
        
        $model->delete();

        Yii::$app->session->setFlash('success', 'Teacher deleted successfully.');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Teacher::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}