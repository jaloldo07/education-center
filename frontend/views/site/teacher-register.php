<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Apply as Teacher');
?>

<style>
    /* 1. Page Background */
    .teacher-register-page {
        padding: 60px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Glass Card */
    .glass-card {
        background: rgba(15, 23, 42, 0.75); /* To'q fon */
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        overflow: hidden;
        color: white;
    }

    /* 3. Header Gradient */
    .glass-header {
        background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
        padding: 40px 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    /* Header orqasidagi porlash effekti */
    .glass-header::before {
        content: '';
        position: absolute;
        top: -50%; left: -50%;
        width: 200%; height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        animation: rotateGlow 10s linear infinite;
    }

    @keyframes rotateGlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .header-content {
        position: relative;
        z-index: 1;
    }

    .header-title {
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 5px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    /* 4. Input Fields (Dark Mode) */
    .form-glass-input {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-glass-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }

    .form-glass-input:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: #4cc9f0; /* Neon Blue Focus */
        color: white;
        box-shadow: 0 0 0 4px rgba(76, 201, 240, 0.2);
        outline: none;
    }

    /* File Input maxsus style */
    .form-glass-input[type="file"] {
        padding: 10px;
        color: rgba(255,255,255,0.7);
    }
    .form-glass-input[type="file"]::-webkit-file-upload-button {
        background: rgba(255,255,255,0.1);
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    .form-glass-input[type="file"]::-webkit-file-upload-button:hover {
        background: var(--primary-color);
    }

    .form-label {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    /* 5. Alerts (Glass Style) */
    .alert-glass-info {
        background: rgba(67, 97, 238, 0.15);
        border: 1px solid rgba(67, 97, 238, 0.3);
        color: #a5b4fc;
        border-radius: 12px;
    }
    
    .alert-glass-warning {
        background: rgba(251, 191, 36, 0.15);
        border: 1px solid rgba(251, 191, 36, 0.3);
        color: #fde68a;
        border-radius: 12px;
    }

    /* 6. Section Titles */
    .section-divider {
        display: flex;
        align-items: center;
        margin: 30px 0 20px 0;
        color: #4cc9f0;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .section-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(255,255,255,0.1);
        margin-left: 15px;
    }

    /* 7. Buttons */
    .btn-neon-submit {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.4);
        transition: 0.3s;
        width: 100%;
    }

    .btn-neon-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 30px rgba(67, 97, 238, 0.6);
        color: white;
    }

    .btn-link-glass {
        color: rgba(255,255,255,0.6);
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-link-glass:hover {
        color: white;
    }

</style>

<div class="teacher-register-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="glass-header">
                        <div class="header-content">
                            <i class="fas fa-chalkboard-teacher fa-3x mb-3 text-white opacity-75"></i>
                            <h2 class="header-title"><?= Html::encode($this->title) ?></h2>
                            <p class="mb-0 text-white-50"><?= Yii::t('app', 'Join our team of expert educators & inspire the future') ?></p>
                        </div>
                    </div>
                    
                    <div class="card-body p-5">
                        <?php $form = ActiveForm::begin([
                            'options' => ['enctype' => 'multipart/form-data'],
                            'fieldConfig' => [
                                'inputOptions' => ['class' => 'form-control form-glass-input'],
                                'labelOptions' => ['class' => 'form-label'],
                            ]
                        ]); ?>

                        <div class="alert alert-glass-info d-flex align-items-center mb-4">
                            <i class="fas fa-info-circle fs-4 me-3"></i>
                            <div>
                                <?= Yii::t('app', 'Fill out the application form below. Our admin team will review your application and contact you within 3-5 business days.') ?>
                            </div>
                        </div>

                        <div class="section-divider">
                            <i class="fas fa-user me-2"></i> <?= Yii::t('app', 'Personal Information') ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'full_name')->textInput([
                                    'placeholder' => 'John Smith'
                                ])->label(Yii::t('app', 'Full Name')) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'email')->textInput([
                                    'type' => 'email', 
                                    'placeholder' => 'john@example.com'
                                ])->label(Yii::t('app', 'Email')) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'phone')->textInput([
                                    'placeholder' => '+998 90 123 45 67'
                                ])->label(Yii::t('app', 'Phone')) ?>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?= $form->field($model, 'subject')->textInput([
                                    'placeholder' => Yii::t('app', 'Mathematics, Physics, etc.')
                                ])->label(Yii::t('app', 'Subject')) ?>
                            </div>
                        </div>

                        <div class="section-divider mt-4">
                            <i class="fas fa-graduation-cap me-2"></i> <?= Yii::t('app', 'Professional Profile') ?>
                        </div>

                        <div class="mb-3">
                            <?= $form->field($model, 'experience_years')->input('number', [
                                'min' => 0, 
                                'placeholder' => Yii::t('app', 'e.g. 5')
                            ])->label(Yii::t('app', 'Years of Experience')) ?>
                        </div>

                        <div class="mb-3">
                            <?= $form->field($model, 'education')->textarea([
                                'rows' => 3, 
                                'placeholder' => Yii::t('app', 'Your degrees, certifications, universities attended...')
                            ])->label(Yii::t('app', 'Education')) ?>
                        </div>

                        <div class="mb-3">
                            <?= $form->field($model, 'bio')->textarea([
                                'rows' => 4, 
                                'placeholder' => Yii::t('app', 'Tell us about yourself, your teaching philosophy, achievements...')
                            ])->label(Yii::t('app', 'Bio / About You')) ?>
                        </div>

                        <div class="section-divider mt-4">
                            <i class="fas fa-file-upload me-2"></i> <?= Yii::t('app', 'Documents') ?>
                        </div>

                        <div class="mb-3">
                            <?= $form->field($model, 'cvFileUpload')->fileInput([
                                'accept' => '.pdf,.doc,.docx'
                            ])->label(Yii::t('app', 'Upload CV/Resume')) ?>
                        </div>

                        <div class="alert alert-glass-warning d-flex align-items-center p-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small><?= Yii::t('app', 'CV must be in PDF or DOC format, maximum 5MB') ?></small>
                        </div>

                        <div class="form-group mt-5">
                            <?= Html::submitButton('<i class="fas fa-paper-plane me-2"></i> ' . Yii::t('app', 'Submit Application'), ['class' => 'btn-neon-submit']) ?>
                        </div>

                        <div class="text-center mt-3">
                            <?= Html::a('<i class="fas fa-arrow-left me-1"></i> ' . Yii::t('app', 'Back to Home'), ['/'], ['class' => 'btn-link-glass']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>