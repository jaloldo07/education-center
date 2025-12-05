<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="course-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9 offset-md-3\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Course Information</h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'name')->textInput([
                'maxlength' => true,
                'placeholder' => 'Course name',
                'class' => 'form-control'
            ]) ?>

            <?= $form->field($model, 'description')->textarea([
                'rows' => 4,
                'placeholder' => 'Course description',
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Course Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- -->
                <div class="col-md-4">
                    <?= $form->field($model, 'duration')->textInput([
                        'type' => 'number',
                        'min' => 1,
                        'placeholder' => 'Months',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'price')->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'step' => '0.01',
                        'placeholder' => 'Price in UZS',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'teacher_id')->dropDownList(
                        $teachers,
                        [
                            'prompt' => 'Select Teacher',
                            'class' => 'form-control'
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-hover']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    

    <?php ActiveForm::end(); ?>
</div>