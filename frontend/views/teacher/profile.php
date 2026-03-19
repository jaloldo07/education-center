<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Teacher */

$this->title = Yii::t('app', 'My Profile');
?>

<div class="teacher-profile dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-tie text-primary"></i> <?= Html::encode($this->title) ?></h2>
        <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to Dashboard'), ['dashboard'], ['class' => 'btn btn-secondary rounded-pill']) ?>
    </div>

    <div class="card shadow-sm content-card" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="card-body">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row text-white">
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true, 'class' => 'form-control bg-dark text-white border-secondary']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control bg-secondary text-white border-secondary', 'title' => 'Emailni o\'zgartirish uchun adminga murojaat qiling']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'form-control bg-dark text-white border-secondary']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'subject')->textInput(['maxlength' => true, 'class' => 'form-control bg-dark text-white border-secondary']) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= $form->field($model, 'experience_years')->textInput(['type' => 'number', 'min' => 0, 'class' => 'form-control bg-dark text-white border-secondary']) ?>
                </div>
                <div class="col-md-12 mb-3">
                    <?= $form->field($model, 'bio')->textarea(['rows' => 4, 'class' => 'form-control bg-dark text-white border-secondary']) ?>
                </div>
            </div>

            <div class="form-group mt-4">
                <?= Html::submitButton('<i class="fas fa-save"></i> ' . Yii::t('app', 'Save Changes'), ['class' => 'btn btn-success rounded-pill px-4']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>