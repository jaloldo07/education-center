<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="teacher-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9 offset-md-3\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'full_name')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'Enter full name',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'subject')->textInput([
                        'maxlength' => true, 
                        'placeholder' => 'e.g., Mathematics',
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Contact Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'experience_years')->textInput([
                        'type' => 'number', 
                        'min' => 0,
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'phone')->textInput([
                        'maxlength' => true, 
                        'placeholder' => '+998 90 123 45 67',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'email')->textInput([
                        'maxlength' => true, 
                        'type' => 'email', 
                        'placeholder' => 'email@example.com',
                        'class' => 'form-control'
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Additional Information</h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'bio')->textarea([
                'rows' => 4, 
                'placeholder' => 'Brief biography and qualifications',
                'class' => 'form-control'
            ]) ?>

            <?= $form->field($model, 'rating')->textInput([
                'type' => 'number', 
                'step' => '0.01', 
                'min' => 0, 
                'max' => 5,
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-hover']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>