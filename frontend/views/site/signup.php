<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Register');
?>

<style>
    .site-signup { padding: 50px 0; font-family: 'Nunito', sans-serif; }
    
    .glass-card {
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        color: white;
    }

    .form-title {
        font-weight: 800; text-align: center; margin-bottom: 10px; font-size: 2rem;
        background: linear-gradient(90deg, #4ade80, #22c55e);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }

    .form-subtitle { text-align: center; color: rgba(255, 255, 255, 0.6); margin-bottom: 30px; }

    .form-glass-input {
        background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1);
        color: white; border-radius: 12px; padding: 12px 15px; transition: all 0.3s ease;
    }
    
    .form-glass-input:focus {
        background: rgba(0, 0, 0, 0.5); border-color: #4ade80; color: white; outline: none;
        box-shadow: 0 0 0 0.25rem rgba(74, 222, 128, 0.25);
    }
    
    /* Calendar icon color fix for date inputs */
    .form-glass-input::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }

    .form-label { font-weight: 600; color: rgba(255, 255, 255, 0.8); margin-bottom: 8px; }
    .form-label i { margin-right: 8px; color: #4ade80; }

    .btn-neon-success {
        background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        border: none; border-radius: 12px; padding: 14px; width: 100%;
        color: #064e3b; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4); transition: all 0.3s ease;
    }
    .btn-neon-success:hover { transform: translateY(-3px); box-shadow: 0 0 30px rgba(74, 222, 128, 0.6); color: #064e3b; }

    .link-light-custom { color: #4ade80; text-decoration: none; font-weight: 700; }
    .link-light-custom:hover { color: #86efac; text-decoration: underline; }

    .animate-up { animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1); }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

    /* 🔥 XATOLIKLAR UCHUN MAXSUS STIL */
    .invalid-feedback {
        color: #ff6b6b !important; /* Och qizil rang (qora fonda yaxshi ko'rinadi) */
        font-size: 0.85rem;
        margin-top: 5px;
        font-weight: 600;
        display: block; /* Doim joy egallab turishi uchun */
    }
    
    .form-control.is-invalid {
        border-color: #ff6b6b !important;
        background-image: none !important; /* Bootstrapning standart xato ikonkasini o'chirish */
    }
</style>

<div class="site-signup">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-8">
            
            <div class="glass-card animate-up">
                <div class="card-body p-5">
                    
                    <h1 class="form-title">
                        <i class="fas fa-user-plus me-2"></i> <?= Html::encode($this->title) ?>
                    </h1>
                    <p class="form-subtitle"><?= Yii::t('app', 'Join our community and start learning today!') ?></p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'form-signup',
                        'enableClientValidation' => true, 
                        'fieldConfig' => [
                            'template' => "{label}\n{input}\n{error}", // 🔥 Error input tagida chiqadi
                            'inputOptions' => ['class' => 'form-control form-glass-input'],
                            'labelOptions' => ['class' => 'form-label'],
                            // 🔥 MUHIM: Xatolik klassi
                            'errorOptions' => ['class' => 'invalid-feedback'], 
                        ]
                    ]); ?>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <?= $form->field($model, 'full_name')->textInput([
                                'placeholder' => 'John Doe',
                                'autofocus' => true
                            ])->label('<i class="fas fa-id-card"></i> ' . Yii::t('app', 'Full Name')) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'username')->textInput([
                                'placeholder' => 'johndoe123'
                            ])->label('<i class="fas fa-user"></i> ' . Yii::t('app', 'Username')) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'email')->textInput([
                                'placeholder' => 'john@example.com',
                                'type' => 'email'
                            ])->label('<i class="fas fa-envelope"></i> ' . Yii::t('app', 'Email')) ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?= $form->field($model, 'password')->passwordInput([
                            'placeholder' => '••••••••'
                        ])->label('<i class="fas fa-lock"></i> ' . Yii::t('app', 'Password')) ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'phone')->textInput([
                                'placeholder' => '+998 90 123 45 67'
                            ])->label('<i class="fas fa-phone"></i> ' . Yii::t('app', 'Phone')) ?>
                        </div>
                        <div class="col-md-6 mb-3">
                            <?= $form->field($model, 'birth_date')->input('date', [
                                'class' => 'form-control form-glass-input' // Klassni qo'lda beramiz
                            ])->label('<i class="fas fa-calendar"></i> ' . Yii::t('app', 'Birth Date')) ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <?= $form->field($model, 'address')->textarea([
                            'rows' => 3,
                            'placeholder' => 'Your full address...',
                            'style' => 'resize: none;'
                        ])->label('<i class="fas fa-map-marker-alt"></i> ' . Yii::t('app', 'Address')) ?>
                    </div>

                    <div class="mb-4 d-flex flex-column align-items-center">
                        <div class="g-recaptcha" data-sitekey="6Lf31HcsAAAAAO2nRbA9wwKX3KEePjEcP3FJgiLi" data-theme="dark"></div>
                        <?= Html::error($model, 'reCaptcha', ['class' => 'invalid-feedback d-block text-center mt-2']) ?>
                    </div>

                    <div class="form-group mt-2">
                        <?= Html::submitButton('<i class="fas fa-rocket me-2"></i> ' . Yii::t('app', 'Create Account'), [
                            'class' => 'btn-neon-success w-100',
                            'name' => 'signup-button'
                        ]) ?>
                    </div>

                    <div class="text-center mt-4">
                        <p class="text-white-50 mb-0">
                            <?= Yii::t('app', 'Already have an account?') ?> 
                            <?= Html::a(Yii::t('app', 'Login here'), ['login'], ['class' => 'link-light-custom']) ?>
                        </p>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>