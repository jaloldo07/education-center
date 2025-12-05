<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Teacher Login';
?>

<style>
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
</style>

<div class="teacher-login-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card shadow-lg border-0 animate__animated animate__fadeInDown">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-chalkboard-teacher fa-3x"></i>
                            </div>
                            <h2 class="fw-bold">Teacher Login</h2>
                            <p class="text-muted">Welcome back, educator!</p>
                        </div>

                        <?php $form = ActiveForm::begin(['id' => 'teacher-login-form']); ?>

                            <?= $form->field($model, 'username')->textInput([
                                'autofocus' => true,
                                'placeholder' => 'Enter your username',
                                'class' => 'form-control form-control-lg'
                            ])->label('<i class="fas fa-user"></i> Username') ?>

                            <?= $form->field($model, 'password')->passwordInput([
                                'placeholder' => 'Enter your password',
                                'class' => 'form-control form-control-lg'
                            ])->label('<i class="fas fa-lock"></i> Password') ?>

                            <?= $form->field($model, 'rememberMe')->checkbox() ?>

                            <div class="d-grid gap-2 mt-4">
                                <?= Html::submitButton('<i class="fas fa-sign-in-alt"></i> Login', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button']) ?>
                            </div>

                        <?php ActiveForm::end(); ?>

                        <hr class="my-4">

                        <div class="text-center">
                            <p class="mb-3 text-muted">
                                <i class="fas fa-info-circle"></i> Not a registered teacher yet?
                            </p>
                            <?= Html::a('<i class="fas fa-paper-plane"></i> Apply as Teacher', 
                                ['site/teacher-register'], 
                                ['class' => 'btn btn-outline-success btn-lg w-100']) ?>
                        </div>

                        <div class="text-center mt-3">
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Home', ['/'], ['class' => 'btn btn-link']) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.teacher-login-page .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
.teacher-login-page .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}
.teacher-login-page .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
</style>