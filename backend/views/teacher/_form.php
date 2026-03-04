<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */
/* @var $user common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important; /* Qizil rang */
        font-weight: bold;
        font-size: 0.9em;
        margin-top: 5px;
        display: block;
    }
    
    .has-error .form-control {
        border-color: #dc3545;
    }
</style>

<div class="teacher-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        // 🔥 BU SOZLAMANI QO'SHDIK:
        'fieldConfig' => [
            'errorOptions' => ['class' => 'help-block-error text-danger'], // Xatolik klassi
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0"><i class="fas fa-user-lock me-2"></i> <?= Yii::t('app', 'Login Credentials') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($user, 'username')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Login (username)',
                        'autocomplete' => 'off'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($user, 'password')->passwordInput([
                        'maxlength' => true,
                        'placeholder' => $user->isNewRecord ? 'Password' : 'Leave blank to keep current password',
                        'autocomplete' => 'new-password'
                    ]) ?>
                </div>
                <div class="col-md-12">
                    <?= $form->field($user, 'email')->textInput([
                        'maxlength' => true, 
                        'type' => 'email',
                        'placeholder' => 'teacher@example.com'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= Yii::t('app', 'Personal Information') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'full_name')->textInput([
                        'maxlength' => true, 
                        'placeholder' => Yii::t('app', 'Enter full name')
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'subject')->textInput([
                        'maxlength' => true, 
                        'placeholder' => Yii::t('app', 'e.g., Mathematics')
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title mb-0"><?= Yii::t('app', 'Contact & Bio') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'experience_years')->textInput([
                        'type' => 'number', 
                        'min' => 0
                    ])->label(Yii::t('app', 'Experience (Years)')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+998...']) ?>
                </div>
            </div>

            <?= $form->field($model, 'bio')->textarea(['rows' => 4]) ?>

            <?= $form->field($model, 'rating')->textInput(['type' => 'number', 'step' => '0.1', 'max' => 5]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-lg']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>