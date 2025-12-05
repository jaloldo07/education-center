<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Schedule;

$this->title = 'Create Schedule - ' . $group->name;
?>

<style>
/* CREATE SCHEDULE PAGE STYLING */

.create-schedule-page {
    padding: 20px 0;
}

.create-schedule-page h4 {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.create-schedule-page .card {
    border-radius: 15px;
    overflow: hidden;
}

.create-schedule-page .card-header {
    background: linear-gradient(45deg, #702bd1ff, #8455dbff);
    padding: 18px 25px;
    border-bottom: none;
    font-size: 1.25rem;
}

.create-schedule-page .card-body {
    background: #f9fafc;
}

/* Form styling */
.create-schedule-page .form-control,
.create-schedule-page .form-select {
    height: 48px;
    border-radius: 10px;
    padding-left: 14px;
    font-size: 15px;
}

.create-schedule-page .form-control:focus,
.create-schedule-page .form-select:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

/* Alerts */
.create-schedule-page .alert-info {
    background: #e8f4ff;
    border: 1px solid #b8ddff;
    border-radius: 10px;
    font-size: 15px;
}

.create-schedule-page .alert-warning {
    background: #fff5e6;
    border: 1px solid #ffd9a3;
    border-radius: 10px;
    font-size: 15px;
}

/* Buttons */
.create-schedule-page .btn-lg {
    padding: 12px 25px;
    border-radius: 10px;
    font-weight: 600;
}

.create-schedule-page .btn-success {
    background: linear-gradient(45deg, #702bd1ff, #8455dbff);
    border: none;
}

.create-schedule-page .btn-success:hover {
    background: linear-gradient(45deg,  #702bd1ff, #8455dbff);
}

.create-schedule-page .btn-secondary {
    background: #6c757d;
}

.create-schedule-page .btn-secondary:hover {
    background: #5a6268;
}

/* Icon improvements */
.create-schedule-page .fa-calendar-plus {
    margin-right: 8px;
}

.create-schedule-page .alert i {
    margin-right: 6px;
}
</style>


<div class="create-schedule-page">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header text-white">
                    <h4 class="mb-0"><i class="fas fa-calendar-plus"></i> <?= Html::encode($this->title) ?></h4>
                </div>
                <div class="card-body p-4">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Create a class schedule for <strong><?= Html::encode($group->name) ?></strong> group
                    </div>

                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'day_of_week')->dropDownList(Schedule::getDayNames(), [
                                'prompt' => 'Select Day',
                                'class' => 'form-control form-select'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'room')->textInput([
                                'placeholder' => 'e.g., Room 101, Online, Building A'
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'start_time')->input('time', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'end_time')->input('time', [
                                'class' => 'form-control'
                            ]) ?>
                        </div>
                    </div>


                    <div class="alert alert-warning">
                        <i class="fas fa-lightbulb"></i> <strong>Tip:</strong> You can create multiple schedules for the same group on different days
                    </div>

                    <div class="form-group mt-4">
                        <?= Html::submitButton('<i class="fas fa-save"></i> Create Schedule', ['class' => 'btn btn-success btn-lg']) ?>
                        <?= Html::a('<i class="fas fa-times"></i> Cancel', ['schedule', 'id' => $group->id], ['class' => 'btn btn-secondary btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>