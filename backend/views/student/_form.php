<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Student */
/* @var $user common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    /* Yii2 va Bootstrap xatolik klasslari uchun majburiy qizil rang */
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important; /* Qip-qizil rang */
        font-weight: bold;
        font-size: 0.9em;
        margin-top: 5px;
        display: block;
    }
    
    /* Input xato bo'lganda uning ramkasi ham qizil bo'lsin */
    .has-error .form-control {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>

<div class="student-form">

    <?php $form = ActiveForm::begin([
        'id' => 'student-form',
        'enableClientValidation' => true,
        // 🔥 MANA SHU YERDA SEHRLI KOD:
        // Bu barcha inputlarga (Email, Login, Telefon...) ta'sir qiladi
        'fieldConfig' => [
            'errorOptions' => ['class' => 'help-block-error text-danger'], 
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <h5 class="card-title mb-0"><i class="fas fa-user-lock me-2"></i> <?= Yii::t('app', 'Tizimga kirish ma\'lumotlari') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($user, 'username')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($user, 'password')->passwordInput(['maxlength' => true, 'autocomplete' => 'new-password']) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($user, 'email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= Yii::t('app', 'Shaxsiy Ma\'lumotlar') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'birth_date')->input('date')->label(Yii::t('app', 'Tug\'ilgan Sana')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'enrolled_date')->input('date')->label(Yii::t('app', 'Qabul Qilingan Sana')) ?>
                </div>
            </div>

            <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Saqlash'), ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Bekor qilish'), ['index'], ['class' => 'btn btn-secondary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>