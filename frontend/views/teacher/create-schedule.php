<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use common\models\Schedule;

$this->title = Yii::t('app', 'Create Schedule') . ' - ' . $course->name;
?>

<style>
    /* ... OLDINGI BARCHA CSS STYLELAR O'ZGARISHSZ QOLADI ... */
    .create-schedule-page { padding-top: 50px; padding-bottom: 80px; font-family: 'Nunito', sans-serif; }
    .glass-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 24px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); overflow: hidden; color: white; }
    .glass-header { background: linear-gradient(135deg, #7209b7 0%, #4361ee 100%); padding: 25px 30px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); display: flex; align-items: center; gap: 15px; }
    .header-icon { font-size: 1.5rem; background: rgba(255,255,255,0.2); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(255,255,255,0.1); }
    .header-title { font-weight: 800; font-size: 1.5rem; margin: 0; text-shadow: 0 2px 5px rgba(0,0,0,0.3); }
    .form-glass-input, .form-select-glass { background: rgba(0, 0, 0, 0.3) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; border-radius: 12px; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease; }
    .form-glass-input:focus, .form-select-glass:focus { background: rgba(0, 0, 0, 0.5) !important; border-color: #4cc9f0 !important; color: white !important; box-shadow: 0 0 0 4px rgba(76, 201, 240, 0.2) !important; outline: none; }
    .form-select-glass { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important; }
    input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    .form-label { font-weight: 700; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-size: 0.9rem; }
    .alert-glass-info { background: rgba(67, 97, 238, 0.15); border: 1px solid rgba(67, 97, 238, 0.3); color: #a5b4fc; border-radius: 12px; padding: 15px; }
    .alert-glass-warning { background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.3); color: #fde68a; border-radius: 12px; padding: 15px; font-size: 0.9rem; }
    .btn-neon-save { background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%); color: #064e3b; border: none; border-radius: 12px; padding: 12px 30px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 20px rgba(74, 222, 128, 0.4); transition: 0.3s; }
    .btn-neon-save:hover { transform: translateY(-3px); box-shadow: 0 0 30px rgba(74, 222, 128, 0.6); color: #064e3b; }
    .btn-glass-cancel { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 12px; padding: 12px 25px; font-weight: 600; transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-glass-cancel:hover { background: rgba(255,255,255,0.2); color: white; }
</style>

<div class="create-schedule-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    
                    <div class="glass-header">
                        <div class="header-icon">
                            <i class="fas fa-calendar-plus text-white"></i>
                        </div>
                        <div>
                            <h4 class="header-title"><?= Html::encode($this->title) ?></h4>
                            <small class="text-white-50">Set up timing and room</small>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        
                        <div class="alert alert-glass-info mb-4 d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fs-4"></i>
                            <div>
                                <?= Yii::t('app', 'Create a class schedule for {course} course', ['course' => '<strong class="text-white">' . Html::encode($course->name) . '</strong>']) ?>
                            </div>
                        </div>

                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'inputOptions' => ['class' => 'form-control form-glass-input'],
                                'labelOptions' => ['class' => 'form-label'],
                            ]
                        ]); ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <?= $form->field($model, 'day_of_week')->dropDownList(Schedule::getDayNames(), [
                                    'prompt' => Yii::t('app', 'Select Day'),
                                    'class' => 'form-select form-select-glass'
                                ])->label(Yii::t('app', 'Day of Week')) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'room')->textInput([
                                    'placeholder' => Yii::t('app', 'e.g., Room 101 or Online')
                                ])->label(Yii::t('app', 'Room / Location')) ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <?= $form->field($model, 'start_time')->input('time')->label(Yii::t('app', 'Start Time')) ?>
                            </div>
                            <div class="col-md-6">
                                <?= $form->field($model, 'end_time')->input('time')->label(Yii::t('app', 'End Time')) ?>
                            </div>
                        </div>

                        <div class="alert alert-glass-warning mb-4">
                            <i class="fas fa-lightbulb me-2"></i> 
                            <strong><?= Yii::t('app', 'Tip:') ?></strong> <?= Yii::t('app', 'You can create multiple schedules for the same course on different days.') ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <?= Html::a('<i class="fas fa-times me-2"></i> ' . Yii::t('app', 'Cancel'), ['schedule', 'id' => $course->id], ['class' => 'btn-glass-cancel']) ?>
                            <?= Html::submitButton('<i class="fas fa-save me-2"></i> ' . Yii::t('app', 'Create Schedule'), ['class' => 'btn-neon-save']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>