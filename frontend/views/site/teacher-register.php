<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Apply as Teacher';
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

<div class="teacher-register-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <i class="fas fa-chalkboard-teacher fa-3x mb-3"></i>
                        <h2 class="mb-0"><?= Html::encode($this->title) ?></h2>
                        <p class="mb-0">Join our team of expert educators</p>
                    </div>
                    
                    <div class="card-body p-5">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Fill out the application form below. Our admin team will review your application and contact you within 3-5 business days.
                        </div>

                        <h5 class="mb-3"><i class="fas fa-user"></i> Personal Information</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'John Smith']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => 'john@example.com']) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'phone')->textInput(['placeholder' => '+998 90 123 45 67']) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'subject')->textInput(['placeholder' => 'Mathematics, Physics, etc.']) ?>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="fas fa-graduation-cap"></i> Professional Information</h5>

                        <?= $form->field($model, 'experience_years')->input('number', ['min' => 0, 'placeholder' => 'Years of teaching experience']) ?>

                        <?= $form->field($model, 'education')->textarea(['rows' => 4, 'placeholder' => 'Your degrees, certifications, universities attended...']) ?>

                        <?= $form->field($model, 'bio')->textarea(['rows' => 5, 'placeholder' => 'Tell us about yourself, your teaching philosophy, achievements...']) ?>

                        <?= $form->field($model, 'cvFileUpload')->fileInput(['accept' => '.pdf,.doc,.docx']) ?>

                        <div class="alert alert-warning">
                            <small><i class="fas fa-exclamation-triangle"></i> CV must be in PDF or DOC format, maximum 5MB</small>
                        </div>

                        <div class="form-group mt-4">
                            <?= Html::submitButton('<i class="fas fa-paper-plane"></i> Submit Application', ['class' => 'btn btn-primary btn-lg w-100']) ?>
                        </div>

                        <div class="text-center mt-3">
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Home', ['/'], ['class' => 'btn btn-link']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>