<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Payment;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */
/* @var $form yii\widgets\ActiveForm */
/* @var $students array */
/* @var $courses array */

?>

<style>
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important; /* Qip-qizil rang */
        font-weight: bold;
        font-size: 0.9rem;
        margin-top: 5px;
        display: block;
    }
    .has-error .form-control, .has-error .form-select {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        background-color: #fff8f8;
    }
    .has-error .control-label {
        color: #dc3545 !important;
    }
</style>

<div class="payment-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            // 🔥 Xatolik klassini qo'shdik
            'errorOptions' => ['class' => 'help-block-error text-danger'], 
            'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
            'labelOptions' => ['class' => 'control-label fw-bold mb-2'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 text-primary"><i class="fas fa-user-graduate"></i> <?= Yii::t('app', 'Student & Course') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'student_id')->dropDownList(
                        $students, 
                        [
                            'prompt' => Yii::t('app', 'Select Student...'),
                            'class' => 'form-select select2'
                        ]
                    )->label(Yii::t('app', 'Student')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'course_id')->dropDownList(
                        $courses, 
                        [
                            'prompt' => Yii::t('app', 'Select Course...'),
                            'class' => 'form-select'
                        ]
                    )->label(Yii::t('app', 'Course')) ?>
                </div>
            </div>
            <div class="alert alert-info py-2 mt-2 mb-0 small border-0 bg-light text-muted">
                <i class="fas fa-info-circle text-info"></i> <?= Yii::t('app', 'Note: Selecting a course will validate if the student is enrolled.') ?>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 text-success"><i class="fas fa-wallet"></i> <?= Yii::t('app', 'Payment Details') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'amount')->textInput([
                        'type' => 'number', 
                        'min' => 0, 
                        'step' => '1000', 
                        'placeholder' => '0.00',
                        'class' => 'form-control font-weight-bold text-success form-control-lg' 
                    ])->label(Yii::t('app', 'Amount (UZS)')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'payment_date')->input('date', [
                        'class' => 'form-control form-control-lg',
                        'value' => $model->isNewRecord ? date('Y-m-d') : $model->payment_date
                    ])->label(Yii::t('app', 'Date')) ?>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <?= $form->field($model, 'payment_method')->dropDownList([
                        'cash' => Yii::t('app', 'Cash'),
                        'card' => Yii::t('app', 'Credit Card (Terminal)'),
                        'click' => Yii::t('app', 'Click / Payme'),
                        'bank_transfer' => Yii::t('app', 'Bank Transfer'),
                    ], [
                        'prompt' => Yii::t('app', 'Select Method...'),
                        'class' => 'form-select form-select-lg'
                    ])->label(Yii::t('app', 'Payment Method')) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'payment_type')->dropDownList([
                        Payment::TYPE_MONTHLY => Yii::t('app', 'Monthly Fee'),
                        Payment::TYPE_YEARLY => Yii::t('app', 'Yearly Fee'),
                        Payment::TYPE_FULL => Yii::t('app', 'Full Course Payment'),
                    ], [
                        'class' => 'form-select form-select-lg'
                    ])->label(Yii::t('app', 'Payment Period')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0 text-secondary"><?= Yii::t('app', 'Additional Info') ?></h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'note')->textarea([
                'rows' => 2, 
                'placeholder' => Yii::t('app', 'Optional comment...'),
            ])->label(Yii::t('app', 'Note')) ?>
        </div>
    </div>

    <div class="form-group mt-4 d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save Payment'), ['class' => 'btn btn-success btn-lg px-5 shadow']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary btn-lg px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>