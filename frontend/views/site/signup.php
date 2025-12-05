<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Register';
?>


<style>

    body {
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
    }


    /* 🔶 Card design */
    .site-signup .card {
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

    /* 🔹 Beautiful header icon + title */
    .site-signup h1 {
        font-weight: 700;
        color: #3d93eaff;
        text-shadow: 0 2px 4px rgba(65, 79, 222, 0.2);
    }

    /* 🔘 Labels */
    .site-signup label {
        font-weight: 600;
        color: #3980c3ff;
    }

    /* 🟦 Inputs */
    .site-signup .form-control {
        height: 48px;
        border-radius: 12px;
        border: 2px solid #e5e7ff;
        transition: 0.3s ease;
    }

    .site-signup textarea.form-control {
        height: auto !important;
    }

    .site-signup .form-control:focus {
        border-color: #4185deff;
        box-shadow: 0 0 0 4px rgba(65, 79, 222, 0.2);
    }

    /* 🔥 Submit button */
    .btn-success {
        background: #419ddeff !important;
        border-color: #418ddeff !important;
        padding: 12px 20px;
        border-radius: 14px;
        font-size: 1.1rem;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .btn-success:hover {
        background: #3597ccff !important;
        box-shadow: 0 8px 20px rgba(65, 149, 222, 0.3);
        transform: translateY(-2px);
    }

    /* 🔘 Button hover animation */
    .btn-hover {
        transition: 0.25s ease-in-out;
    }

    /* 🔹 Card body spacing */
    .site-signup .card-body {
        padding: 40px 45px !important;
    }

    /* 🔽 Fade animation */
    .animate__fadeInDown {
        animation-duration: 0.6s !important;
    }

    /* 🔗 Login link */
    .site-signup a.text-primary {
        color: #4190deff !important;
    }

    .site-signup a.text-primary:hover {
        text-decoration: underline;
    }
</style>



<div class="site-signup">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow animate__animated animate__fadeInDown">
                <div class="card-body p-5">
                    <h1 class="text-center mb-4">
                        <i class="fas fa-user-plus text-success"></i> <?= Html::encode($this->title) ?>
                    </h1>

                    <p class="text-center text-muted">Please fill out the following fields to register:</p>

                    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                    <?= $form->field($model, 'username')->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Choose a username',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-user"></i> Username') ?>

                    <?= $form->field($model, 'email')->textInput([
                        'placeholder' => 'Enter your email',
                        'type' => 'email',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-envelope"></i> Email') ?>

                    <?= $form->field($model, 'password')->passwordInput([
                        'placeholder' => 'Choose a strong password',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-lock"></i> Password') ?>

                    <?= $form->field($model, 'full_name')->textInput([
                        'placeholder' => 'Enter your full name',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-id-card"></i> Full Name') ?>

                    <?= $form->field($model, 'phone')->textInput([
                        'placeholder' => '+998 90 123 45 67',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-phone"></i> Phone') ?>

                    <?= $form->field($model, 'birth_date')->input('date', [
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-calendar"></i> Birth Date') ?>

                    <?= $form->field($model, 'address')->textarea([
                        'rows' => 3,
                        'placeholder' => 'Enter your address',
                        'class' => 'form-control'
                    ])->label('<i class="fas fa-map-marker-alt"></i> Address') ?>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('<i class="fas fa-user-plus"></i> Register', [
                            'class' => 'btn btn-success btn-lg w-100 btn-hover',
                            'name' => 'signup-button'
                        ]) ?>
                    </div>

                    <div class="text-center mt-3">
                        <p>Already have an account? <?= Html::a('Login here', ['login'], ['class' => 'text-primary fw-bold']) ?></p>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>