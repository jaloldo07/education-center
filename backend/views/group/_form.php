<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Course;
use common\models\Teacher;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Group */
/* @var $form yii\widgets\ActiveForm */

$courses = ArrayHelper::map(Course::find()->all(), 'id', 'name');
$teachers = ArrayHelper::map(Teacher::find()->all(), 'id', 'full_name');

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
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
    .has-error .control-label {
        color: #dc3545;
    }
</style>

<div class="group-form">

    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-12\">{input}</div>\n<div class=\"col-md-12\">{error}</div>",
            'labelOptions' => ['class' => 'control-label fw-bold mb-1'],
            // 🔥 Xatolik klassini qo'shdik
            'errorOptions' => ['class' => 'help-block-error text-danger'],
            'inputOptions' => ['class' => 'form-control'],
        ],
    ]); ?>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="fas fa-layer-group"></i> <?= Yii::t('app', 'Group Information') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true, 
                        'placeholder' => Yii::t('app', 'e.g., PHP-Start-01'),
                    ])->label(Yii::t('app', 'Group Name')) ?>
                </div>

                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList([
                        'active' => Yii::t('app', 'Active'),
                        'pending' => Yii::t('app', 'Pending'),
                        'finished' => Yii::t('app', 'Finished'),
                    ], [
                        'class' => 'form-select'
                    ])->label(Yii::t('app', 'Status')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="fas fa-link"></i> <?= Yii::t('app', 'Assign Course & Teacher') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'course_id')->dropDownList(
                        $courses, 
                        [
                            'prompt' => Yii::t('app', 'Select Course...'),
                            'class' => 'form-select'
                        ]
                    )->label(Yii::t('app', 'Course')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'teacher_id')->dropDownList(
                        $teachers, 
                        [
                            'prompt' => Yii::t('app', 'Select Teacher...'),
                            'class' => 'form-select'
                        ]
                    )->label(Yii::t('app', 'Main Teacher')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="far fa-calendar-alt"></i> <?= Yii::t('app', 'Schedule & Location') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                     <?= $form->field($model, 'schedule')->textInput([
                         'placeholder' => Yii::t('app', 'e.g., Mon-Wed-Fri 18:00'),
                     ])->label(Yii::t('app', 'Schedule (Days/Time)')) ?>
                </div>
                <div class="col-md-6">
                     <?= $form->field($model, 'room')->textInput([
                         'placeholder' => Yii::t('app', 'e.g., Room 204'),
                     ])->label(Yii::t('app', 'Room Number')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save Group'), ['class' => 'btn btn-success btn-lg px-4']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary btn-lg px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>