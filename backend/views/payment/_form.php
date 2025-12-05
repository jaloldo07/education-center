<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Payment;

?>

<div class="payment-form">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-md-9\">{input}</div>\n<div class=\"col-md-9 offset-md-3\">{error}</div>",
            'labelOptions' => ['class' => 'col-md-3 control-label'],
        ],
    ]); ?>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Payment Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'student_id')->dropDownList(
                        $students, 
                        [
                            'prompt' => 'Select Student',
                            'class' => 'form-control'
                        ]
                    ) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'course_id')->dropDownList(
                        $courses, 
                        [
                            'prompt' => 'Select Course',
                            'class' => 'form-control'
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h5 class="card-title">Payment Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'amount')->textInput([
                        'type' => 'number', 
                        'min' => 0, 
                        'step' => '0.01', 
                        'placeholder' => 'Amount in UZS',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'payment_date')->input('date', [
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'payment_type')->dropDownList([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ], [
                        'prompt' => 'Select Type',
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
            <?= $form->field($model, 'note')->textarea([
                'rows' => 3, 
                'placeholder' => 'Additional notes (optional)',
                'class' => 'form-control'
            ]) ?>
        </div>
    </div>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> Save Payment', ['class' => 'btn btn-success btn-hover']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>