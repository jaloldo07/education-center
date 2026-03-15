<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Enrollment; // 🔥 backend o'rniga common

?>

<style>
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important;
        font-weight: bold;
        font-size: 0.9rem;
        margin-top: 5px;
        display: block;
    }
    .has-error .form-control {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
</style>

<div class="enrollment-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9 offset-md-3\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
            'errorOptions' => ['class' => 'help-block-error text-danger'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title"><?= Yii::t('app', 'Enrollment Information') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'student_id')->dropDownList(
                        $students, 
                        [
                            'prompt' => Yii::t('app', 'Select Student'),
                        ]
                    )->label(Yii::t('app', 'Student')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'course_id')->dropDownList(
                        $courses, 
                        [
                            'prompt' => Yii::t('app', 'Select Course'),
                        ]
                    )->label(Yii::t('app', 'Course')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title"><?= Yii::t('app', 'Enrollment Details') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'enrolled_on')->input('date')->label(Yii::t('app', 'Enrolled On')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList([
                        'active' => Yii::t('app', 'Active'),
                        'completed' => Yii::t('app', 'Completed'),
                        'waiting_payment' => Yii::t('app', 'Waiting Payment'), // 🔥 Yangi statusingizni ham qo'shib qo'ydim
                        'cancelled' => Yii::t('app', 'Cancelled'),
                    ], [
                        'prompt' => Yii::t('app', 'Select Status'),
                    ])->label(Yii::t('app', 'Status')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success btn-hover']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>