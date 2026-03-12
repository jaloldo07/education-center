<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Login');
?>

<style>
    /* 🔮 BACKGROUND: Purple Gradient + Overlay */
    body {
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* 🔹 Login Card */
    .card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    /* 🟣 Title Icon */
    h1 i {
        color: #419cebff !important;
    }

    /* 🟪 Form Labels with Icons */
    label i {
        color: #3e89e4ff;
        margin-right: 6px;
    }

    /* 🔵 Inputs */
    .form-control-lg {
        height: 48px;
        border-radius: 12px;
        border: 1px solid #d8b4fe;
        transition: 0.3s ease;
    }

    .form-control-lg:focus {
        border-color: #308fcaff;
        box-shadow: 0 0 8px rgba(58, 162, 237, 0.5);
    }

    /* 🔥 Login Button */
    .btn-hover {
        background: linear-gradient(135deg, #33b0eaff, #3a82edff, #285dd9ff) !important;
        border: none;
        color: white;
        padding: 12px;
        border-radius: 12px;
        font-weight: bold;
        transition: 0.3s ease;
    }

    .btn-hover:hover {
        background: linear-gradient(135deg, #5599f7ff, #3392eaff);
        box-shadow: 0 10px 25px rgba(57, 154, 219, 0.45);
    }

    /* 🔸 Admin Button */
    .btn-outline-danger {
        border-radius: 12px !important;
        padding: 10px !important;
        border-color: #fb7185 !important;
        color: #fb7185 !important;
        transition: 0.3s ease;
    }

    .btn-outline-danger:hover {
        background: #fb7185 !important;
        color: white !important;
    }

    /* ✨ Divider */
    hr {
        border-top: 1px solid rgba(255, 255, 255, 0.4);
    }

    /* Small text */
    .text-muted {
        color: #ede9fe !important;
    }
</style>



<div class="site-login">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow animate__animated animate__fadeInDown">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4">
                        <i class="fas fa-sign-in-alt text-primary"></i> <?= Html::encode($this->title) ?>
                    </h1>

                    <p class="text-center text-muted"><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => Yii::t('app', 'Enter your username'),
                        'class' => 'form-control form-control-lg'
                    ])->label('<i class="fas fa-user"></i> ' . Yii::t('app', 'Username')) ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => Yii::t('app', 'Enter your password'),
                        'class' => 'form-control form-control-lg'
                    ])->label('<i class="fas fa-lock"></i> ' . Yii::t('app', 'Password')) ?>

                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'label' => Yii::t('app', 'Remember Me') // Model labelini override qilamiz
                    ]) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Login'), ['class' => 'btn btn-primary btn-lg w-100 btn-hover', 'name' => 'login-button']) ?>
                    </div>

                    <div class="text-center mt-3">
                        <p><?= Yii::t('app', 'Don\'t have an account?') ?> <?= Html::a(Yii::t('app', 'Register here'), ['signup'], ['class' => 'text-primary']) ?></p>
                    </div>

                    <?php ActiveForm::end(); ?>

                    <hr class="my-4">

                    <div class="text-center">
   						<p class="text-muted mb-2">
        					<i class="fas fa-shield-alt"></i> <?= Yii::t('app', 'Are you an administrator?') ?>
    					</p>
    					<?= Html::a(
        				'<i class="fas fa-user-shield"></i> ' . Yii::t('app', 'Admin Panel Login'),
        				'/admin',
       				 	[
            			'class' => 'btn btn-outline-danger w-100',
            			'target' => '_blank'
        				]
   						 ) ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>