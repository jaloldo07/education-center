<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Course; 

/* @var $this yii\web\View */
/* @var $model common\models\Course */
/* @var $form yii\widgets\ActiveForm */
/* @var $teachers array */

?>

<style>
    .help-block, .help-block-error, .invalid-feedback {
        color: #dc3545 !important; /* Qip-qizil rang */
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

<div class="course-form">

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
            <h5 class="card-title mb-0"><i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Course Information') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'name')->textInput([
                        'maxlength' => true,
                        'placeholder' => Yii::t('app', 'Enter course name'),
                    ])->label(Yii::t('app', 'Name')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'type')->dropDownList(
                        Course::getTypeOptions(), 
                        ['class' => 'form-select']
                    )->label(Yii::t('app', 'Enrollment Type')) ?>
                </div>
            </div>

            <?= $form->field($model, 'description')->textarea([
                'rows' => 6,
                'placeholder' => Yii::t('app', 'Write a detailed description of the course...'),
            ])->label(Yii::t('app', 'Description')) ?>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0"><i class="fas fa-cogs"></i> <?= Yii::t('app', 'Settings & Pricing') ?></h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'duration')->textInput([
                        'type' => 'number',
                        'min' => 1,
                        'placeholder' => Yii::t('app', 'Months'),
                    ])->label(Yii::t('app', 'Duration (Months)')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'price')->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'step' => '1000',
                        'placeholder' => Yii::t('app', 'Price in UZS'),
                    ])->label(Yii::t('app', 'Price (UZS)')) ?>
                </div>
                
                <div class="col-md-4">
                    <?= $form->field($model, 'teacher_id')->dropDownList(
                        $teachers, 
                        [
                            'prompt' => Yii::t('app', 'Select Instructor...'),
                            'class' => 'form-select'
                        ]
                    )->label(Yii::t('app', 'Instructor')) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group d-flex gap-2">
        <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save Course'), ['class' => 'btn btn-success btn-lg px-4']) ?>
        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Cancel'), ['index'], ['class' => 'btn btn-secondary btn-lg px-4']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>