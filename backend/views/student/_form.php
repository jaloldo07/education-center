<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

?>

<div class="student-form">
    <?php $form = ActiveForm::begin(); ?>

    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> User account will be created automatically with default password: <strong>student123</strong>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'Full Name']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'email')->textInput(['type' => 'email', 'placeholder' => 'email@example.com']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'phone')->textInput(['placeholder' => '+998 90 123 45 67']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'birth_date')->input('date') ?>
        </div>
    </div>

    <?= $form->field($model, 'address')->textarea(['rows' => 3, 'placeholder' => 'Address']) ?>

    <?= $form->field($model, 'enrolled_date')->input('date') ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('<i class="fas fa-save"></i> Save', ['class' => 'btn btn-success btn-hover']) ?>
        <?= Html::a('<i class="fas fa-times"></i> Cancel', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>