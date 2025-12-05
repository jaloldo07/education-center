<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Student;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $full_name;
    public $phone;
    public $birth_date;
    public $address;

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['full_name', 'phone'], 'required'],
            [['birth_date', 'address'], 'safe'],
        ];
    }

    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // ✨ Auto username yaratish
            $nameParts = explode(' ', $this->full_name);
            $firstName = strtolower($nameParts[0]);

            // Username unique bo'lishi kerak
            $username = $firstName;
            $counter = 1;
            while (User::findOne(['username' => $username])) {
                $username = $firstName . $counter;
                $counter++;
            }

            // ✨ Auto password
            $autoPassword = $username . '123';

            $user = new User();
            $user->username = $username;  // Auto username
            $user->email = $this->email;
            $user->setPassword($this->password);  // User'ning o'zi tanlagan parol
            $user->generateAuthKey();
            $user->role = User::ROLE_STUDENT;

            if (!$user->save()) {
                throw new \Exception('Unable to save user');
            }

            $student = new Student();
            $student->user_id = $user->id;
            $student->full_name = $this->full_name;
            $student->email = $this->email;
            $student->phone = $this->phone;
            $student->birth_date = $this->birth_date;
            $student->address = $this->address;
            $student->enrolled_date = date('Y-m-d');

            if (!$student->save()) {
                throw new \Exception('Unable to save student');
            }

            $transaction->commit();

            // ✨ Flash message'da credentials ko'rsatish
            Yii::$app->session->setFlash(
                'success',
                "Registration successful!<br><strong>Username:</strong> {$username}<br>Please remember your credentials for login."
            );

            return $user;
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage());
            return null;
        }
    }
}
