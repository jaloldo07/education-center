<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Course;

/* @var $this yii\web\View */
/* @var $model common\models\Test */

$this->title = Yii::t('app', 'Create New Test');
?>

<style>
    /* ... BARCHA CSS STYLARLAR O'ZGARISHSZ QOLADI ... */
    .create-test-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); overflow: hidden; }
    .glass-header { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); padding: 30px; color: white; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .header-title h2 { font-weight: 800; margin: 0; font-size: 1.8rem; text-shadow: 0 0 15px rgba(67, 97, 238, 0.5); }
    .form-section { background: rgba(255,255,255,0.03); padding: 25px; border-radius: 16px; margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.05); position: relative; overflow: hidden; }
    .form-section::before { content: ''; position: absolute; top: 0; left: 0; bottom: 0; width: 4px; }
    .section-blue::before { background: #4361ee; } .title-blue { color: #4361ee; }
    .section-pink::before { background: #f72585; } .title-pink { color: #f72585; }
    .section-green::before { background: #4ade80; } .title-green { color: #4ade80; }
    .section-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    .form-glass-control { background: rgba(0, 0, 0, 0.3) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; border-radius: 12px; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease; }
    .form-glass-control:focus { background: rgba(0, 0, 0, 0.5) !important; border-color: #4361ee !important; color: white !important; box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.2) !important; outline: none; }
    .form-glass-control::placeholder { color: rgba(255, 255, 255, 0.4) !important; }
    .form-glass-control option { background-color: #1e293b; color: white; }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    .form-label { font-weight: 600; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-size: 0.9rem; }
    .form-text { color: rgba(255,255,255,0.4); font-size: 0.8rem; margin-top: 5px; }
    .btn-create-neon { background: linear-gradient(135deg, #4ade80, #22c55e); color: #064e3b; border: none; padding: 12px 40px; border-radius: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 20px rgba(74, 222, 128, 0.4); transition: 0.3s; }
    .btn-create-neon:hover { transform: translateY(-3px); box-shadow: 0 0 30px rgba(74, 222, 128, 0.6); color: #064e3b; }
    .btn-glass-back { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 12px; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-glass-back:hover { background: white; color: black; }
    .checkbox-glass { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
</style>

<div class="create-test-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="glass-header">
                        <div class="header-title">
                            <h2><i class="bi bi-plus-circle me-2"></i> <?= Html::encode($this->title) ?></h2>
                            <small class="text-white-50"><?= Yii::t('app', 'Configure general settings and schedule') ?></small>
                        </div>
                        <?= Html::a('<i class="bi bi-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn-glass-back']) ?>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <?php $form = ActiveForm::begin([
                            'options' => ['class' => 'test-form'],
                            'fieldConfig' => [
                                'inputOptions' => ['class' => 'form-control form-glass-control'],
                                'labelOptions' => ['class' => 'form-label'],
                                'hintOptions' => ['class' => 'form-text'],
                            ]
                        ]); ?>

                        <div class="form-section section-blue">
                            <h5 class="section-title title-blue"><i class="bi bi-info-circle-fill"></i> <?= Yii::t('app', 'Basic Information') ?></h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'title')->textInput([
                                        'placeholder' => Yii::t('app', 'e.g., Midterm Exam - Mathematics')
                                    ])->label(Yii::t('app', 'Test Title')) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'status')->dropDownList([
                                        'draft' => '📝 ' . Yii::t('app', 'Draft'),
                                        'active' => '✅ ' . Yii::t('app', 'Active'),
                                        'closed' => '🔒 ' . Yii::t('app', 'Closed')
                                    ], ['class' => 'form-select form-glass-control'])->label(Yii::t('app', 'Status')) ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'course_id')->dropDownList(
                                        ArrayHelper::map($courses, 'id', 'name'),
                                        ['prompt' => Yii::t('app', 'Select Course...'), 'class' => 'form-select form-glass-control']
                                    )->label(Yii::t('app', 'Course')) ?>
                                </div>
                            </div>

                            <?= $form->field($model, 'description')->textarea([
                                'rows' => 3,
                                'placeholder' => Yii::t('app', 'Instructions for students...')
                            ])->label(Yii::t('app', 'Description')) ?>
                        </div>

                        <div class="form-section section-pink">
                            <h5 class="section-title title-pink"><i class="bi bi-sliders"></i> <?= Yii::t('app', 'Test Rules & Settings') ?></h5>

                            <div class="row">
                                <div class="col-md-3">
                                    <?= $form->field($model, 'duration')->textInput([
                                        'type' => 'number', 'min' => 1, 'placeholder' => '60'
                                    ])->label(Yii::t('app', 'Duration (min)'))->hint(Yii::t('app', 'Time limit in minutes')) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, 'passing_score')->textInput([
                                        'type' => 'number', 'min' => 0, 'max' => 100, 'placeholder' => '60'
                                    ])->label(Yii::t('app', 'Pass Score (%)'))->hint(Yii::t('app', 'Minimum % to pass')) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, 'max_attempts')->dropDownList([
                                        0 => Yii::t('app', 'Unlimited (Cheksiz)'),
                                        1 => '1 marta',
                                        2 => '2 marta',
                                        3 => '3 marta',
                                        4 => '4 marta',
                                        5 => '5 marta',
                                        10 => '10 marta',
                                    ], ['class' => 'form-select form-glass-control'])->label(Yii::t('app', 'Max Attempts'))->hint(Yii::t('app', 'How many times student can retake')) ?>
                                </div>
                                <div class="col-md-3">
                                    <div class="checkbox-glass mt-1">
                                        <div class="form-check form-switch">
                                            <?= $form->field($model, 'require_face_control')->checkbox([
                                                'class' => 'form-check-input',
                                                'labelOptions' => ['class' => 'form-check-label text-white fw-bold']
                                            ])->hint(Yii::t('app', 'Takes photo before starting')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-section section-green">
                            <h5 class="section-title title-green"><i class="bi bi-calendar-range"></i> <?= Yii::t('app', 'Availability Schedule') ?></h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'start_date')->input('datetime-local')->label(Yii::t('app', 'Opens At'))->hint(Yii::t('app', 'Test becomes available')) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'end_date')->input('datetime-local')->label(Yii::t('app', 'Closes At'))->hint(Yii::t('app', 'Deadline for submission')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'text-white-50 text-decoration-none']) ?>
                            <?= Html::submitButton('<i class="bi bi-check-lg me-2"></i> ' . Yii::t('app', 'Save Test'), ['class' => 'btn-create-neon']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>