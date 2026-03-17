<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Update Test') . ': ' . $model->title;
?>

<style>
    /* 1. Page Container */
    .update-test-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    /* 2. Glass Card */
    .glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); overflow: hidden; }
    /* 3. Header Gradient (Purple/Pink for Update) */
    .glass-header { background: linear-gradient(135deg, #7209b7 0%, #f72585 100%); padding: 30px; color: white; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .header-title h2 { font-weight: 800; margin: 0; font-size: 1.8rem; text-shadow: 0 0 15px rgba(247, 37, 133, 0.5); }
    /* 4. Form Sections */
    .form-section { background: rgba(255,255,255,0.03); padding: 25px; border-radius: 16px; margin-bottom: 25px; border: 1px solid rgba(255,255,255,0.05); position: relative; overflow: hidden; }
    .form-section::before { content: ''; position: absolute; top: 0; left: 0; bottom: 0; width: 4px; }
    .section-purple::before { background: #b5179e; } .title-purple { color: #b5179e; }
    .section-orange::before { background: #f8961e; } .title-orange { color: #f8961e; }
    .section-cyan::before { background: #4cc9f0; } .title-cyan { color: #4cc9f0; }
    .section-title { font-weight: 700; font-size: 1.1rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
    /* 5. Inputs (Dark Mode) */
    .form-glass-control { background: rgba(0, 0, 0, 0.3) !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; color: white !important; border-radius: 12px; padding: 12px 15px; font-size: 1rem; transition: all 0.3s ease; }
    .form-glass-control:focus { background: rgba(0, 0, 0, 0.5) !important; border-color: #f72585 !important; color: white !important; box-shadow: 0 0 0 4px rgba(247, 37, 133, 0.2) !important; outline: none; }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    .form-label { font-weight: 600; color: rgba(255, 255, 255, 0.7); margin-bottom: 8px; font-size: 0.9rem; }
    .form-text { color: rgba(255,255,255,0.4); font-size: 0.8rem; margin-top: 5px; }
    /* 6. Buttons */
    .btn-update-neon { background: linear-gradient(135deg, #f72585, #b5179e); color: white; border: none; padding: 12px 40px; border-radius: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 0 20px rgba(247, 37, 133, 0.4); transition: 0.3s; }
    .btn-update-neon:hover { transform: translateY(-3px); box-shadow: 0 0 30px rgba(247, 37, 133, 0.6); color: white; }
    .btn-glass-back { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 12px; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-glass-back:hover { background: white; color: black; }
    .checkbox-glass { background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); }
</style>

<div class="update-test-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="glass-header">
                        <div class="header-title">
                            <h2><i class="bi bi-pencil-square me-2"></i> <?= Yii::t('app', 'Update Test') ?></h2>
                            <small class="text-white-50"><?= Yii::t('app', 'Editing:') ?> <strong><?= Html::encode($model->title) ?></strong></small>
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

                        <div class="form-section section-purple">
                            <h5 class="section-title title-purple"><i class="bi bi-info-circle-fill"></i> <?= Yii::t('app', 'Basic Information') ?></h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <?= $form->field($model, 'title')->textInput([
                                        'placeholder' => 'e.g., Midterm Exam - Mathematics'
                                    ])->label(Yii::t('app', 'Test Title')) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'status')->dropDownList([
                                        'draft' => '📝 Draft',
                                        'active' => '✅ Active',
                                        'closed' => '🔒 Closed'
                                    ], ['class' => 'form-select form-glass-control'])->label(Yii::t('app', 'Status')) ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'course_id')->dropDownList(
                                        ArrayHelper::map($courses, 'id', 'name'),
                                        ['prompt' => 'Select Course...', 'class' => 'form-select form-glass-control']
                                    )->label(Yii::t('app', 'Course')) ?>
                                </div>
                            </div>

                            <?= $form->field($model, 'description')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Instructions for students...'
                            ])->label(Yii::t('app', 'Description')) ?>
                        </div>

                        <div class="form-section section-orange">
                            <h5 class="section-title title-orange"><i class="bi bi-sliders"></i> <?= Yii::t('app', 'Test Rules & Settings') ?></h5>

                            <div class="row">
                                <div class="col-md-3">
                                    <?= $form->field($model, 'duration')->textInput([
                                        'type' => 'number', 'min' => 1
                                    ])->label(Yii::t('app', 'Duration (min)')) ?>
                                </div>
                                <div class="col-md-3">
                                    <?= $form->field($model, 'passing_score')->textInput([
                                        'type' => 'number', 'min' => 0, 'max' => 100
                                    ])->label(Yii::t('app', 'Pass Score (%)')) ?>
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
                                    ], ['class' => 'form-select form-glass-control'])->label(Yii::t('app', 'Max Attempts')) ?>
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

                        <div class="form-section section-cyan">
                            <h5 class="section-title title-cyan"><i class="bi bi-calendar-range"></i> <?= Yii::t('app', 'Availability Schedule') ?></h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <?= $form->field($model, 'start_date')->input('datetime-local', [
                                        'value' => $model->start_date ? date('Y-m-d\TH:i', strtotime($model->start_date)) : ''
                                    ])->label(Yii::t('app', 'Opens At')) ?>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'end_date')->input('datetime-local', [
                                        'value' => $model->end_date ? date('Y-m-d\TH:i', strtotime($model->end_date)) : ''
                                    ])->label(Yii::t('app', 'Closes At')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <?= Html::a(Yii::t('app', 'Cancel'), ['index'], ['class' => 'text-white-50 text-decoration-none']) ?>
                            <?= Html::submitButton('<i class="bi bi-check-circle me-2"></i> ' . Yii::t('app', 'Update Test'), ['class' => 'btn-update-neon']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>