<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="group-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9 offset-md-3\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Group Information</h5>
        </div>
        <div class="card-body">
            <?= $form->field($model, 'name')->textInput([
                'maxlength' => true, 
                'placeholder' => 'e.g., Group A1',
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Assign Course and Teacher</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'course_id')->dropDownList(
                        $courses, 
                        [
                            'prompt' => 'Select Course',
                            'class' => 'form-control'
                        ]
                    ) ?>
                </div>
                <div class="col-md-6">
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