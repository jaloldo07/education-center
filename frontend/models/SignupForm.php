<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Student;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $birth_date;
    public $address;
    
    // 🔥 YANGI QO'SHILDI: reCAPTCHA uchun xususiyat
    public $reCaptcha;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Bu login allaqachon band.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Bu email allaqachon band.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            // 🔥 STUDENT MA'LUMOTLARI
            [['full_name', 'phone', 'birth_date'], 'required'],
            [['address'], 'string'],
            [['full_name'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],

            // 🔥 MUHIM: YOSH CHEGARASI (7 YOSH)
            ['birth_date', 'date', 'format' => 'php:Y-m-d', 
                'max' => date('Y-m-d', strtotime('-7 years')), 
                'tooBig' => Yii::t('app', 'Siz kamida 7 yosh bo\'lishingiz kerak.'),
                'message' => Yii::t('app', 'Sana noto\'g\'ri formatda.')
            ],

            // 🔥 YANGI QO'SHILDI: reCAPTCHA tekshiruvi
            [['reCaptcha'], 'validateRecaptcha', 'skipOnEmpty' => false],
        ];
    }

    /**
     * 🔥 YANGI QO'SHILDI: Google reCAPTCHA ni tekshiruvchi maxsus funksiya
     */
    public function validateRecaptcha($attribute, $params)
    {
        // 🔴 DIQQAT: SHU YERGA GOOGLE DAN OLGAN "SECRET KEY" NI YOZING!
        $secretKey = '6Lf31HcsAAAAANV0s967MvCufRMNBLPmHwwr-5mQ'; 
        
        $response = Yii::$app->request->post('g-recaptcha-response');

        if (empty($response)) {
            $this->addError($attribute, Yii::t('app', 'Iltimos, robot emasligingizni tasdiqlang!'));
            return;
        }

        $url = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => Yii::$app->request->userIP
        ];

        $options = [
            'http' => [
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $responseData = json_decode($result);

        if (!$responseData || !$responseData->success) {
            $this->addError($attribute, Yii::t('app', 'reCAPTCHA xato. Iltimos sahifani yangilab, qayta urinib ko\'ring.'));
        }
    }

    /**
     * Inputlar nomini chiroyli chiqarish uchun
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Login'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Parol'),
            'full_name' => Yii::t('app', 'F.I.Sh'),
            'phone' => Yii::t('app', 'Telefon'),
            'birth_date' => Yii::t('app', 'Tug\'ilgan sana'),
            'address' => Yii::t('app', 'Manzil'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. USER YARATISH
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;
            $user->role = User::ROLE_STUDENT; 

            if (!$user->save()) {
                throw new \Exception('Userni saqlab bo\'lmadi.');
            }

            // 2. STUDENT PROFILINI YARATISH
            $student = new Student();
            $student->user_id = $user->id;
            $student->full_name = $this->full_name;
            $student->phone = $this->phone;
            $student->email = $this->email; 
            $student->birth_date = $this->birth_date;
            $student->address = $this->address;
            $student->enrolled_date = date('Y-m-d'); // Bugungi sana

            if (!$student->save()) {
                throw new \Exception('Student profilini saqlab bo\'lmadi.');
            }

            $transaction->commit();
            return $user;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            return null;
        }
    }
}