<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Lesson */
/* @var $courses common\models\Course[] */

$this->title = Yii::t('app', 'Create Lesson');
?>

<style>
    /* 1. Page Container */
    .create-lesson-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Glass Card */
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        overflow: hidden;
    }

    /* 3. Header Gradient */
    .glass-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        padding: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .header-title h2 {
        font-weight: 800;
        margin: 0;
        font-size: 1.8rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    /* 4. Form Sections */
    .form-section {
        background: rgba(255,255,255,0.05);
        border-left: 4px solid #4361ee;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
    }

    .section-title {
        color: #4cc9f0;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 15px;
        display: flex; align-items: center; gap: 10px;
    }

    /* 5. Inputs (Dark Mode) */
    .form-glass-control {
        background: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-glass-control:focus {
        background: rgba(0, 0, 0, 0.5) !important;
        border-color: #4cc9f0 !important;
        color: white !important;
        box-shadow: 0 0 0 4px rgba(76, 201, 240, 0.2) !important;
        outline: none;
    }

    /* Placeholder rangi (Muted) */
    .form-glass-control::placeholder {
        color: rgba(255, 255, 255, 0.4) !important;
    }

    /* Select Input ichidagi Optionlar qora bo'lishi kerak */
    .form-glass-control option {
        background-color: #1e293b; /* To'q fon */
        color: white;
    }

    /* File Input Style */
    .form-glass-control[type="file"]::-webkit-file-upload-button {
        background: rgba(255,255,255,0.1);
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        margin-right: 10px;
        cursor: pointer;
        transition: 0.3s;
    }
    .form-glass-control[type="file"]::-webkit-file-upload-button:hover {
        background: var(--primary-color);
    }

    .form-label {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .field-hint {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.5);
        margin-top: 5px;
    }

    /* 6. Buttons */
    .btn-create-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
    }
    .btn-create-neon:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-glass-back:hover { background: white; color: black; }

    /* Content Toggle Animation */
    .content-toggle {
        animation: fadeIn 0.4s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

</style>

<div class="create-lesson-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="glass-header">
                        <div class="header-title">
                            <h2>✨ <?= Html::encode($this->title) ?></h2>
                            <small class="text-white-50"><?= Yii::t('app', 'Create engaging educational content') ?></small>
                        </div>
                        <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn-glass-back']) ?>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <?php $form = ActiveForm::begin([
                            'options' => ['enctype' => 'multipart/form-data'],
                            'fieldConfig' => [
                                'inputOptions' => ['class' => 'form-control form-glass-control'],
                                'labelOptions' => ['class' => 'form-label'],
                            ]
                        ]); ?>

                        <div class="form-section">
                            <h5 class="section-title"><i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Basic Information') ?></h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'course_id')->dropDownList(
                                        ArrayHelper::map($courses, 'id', 'name'),
                                        ['prompt' => Yii::t('app', 'Select Course...'), 'class' => 'form-select form-glass-control']
                                    )->label(Yii::t('app', 'Course')) ?>
                                </div>
                            </div>

                            <?= $form->field($model, 'title')->textInput([
                                'placeholder' => Yii::t('app', 'e.g., Introduction to Variables')
                            ])->label(Yii::t('app', 'Lesson Title')) ?>

                            <?= $form->field($model, 'description')->textarea([
                                'rows' => 3,
                                'placeholder' => Yii::t('app', 'Brief description of what students will learn...')
                            ])->label(Yii::t('app', 'Description')) ?>
                        </div>

                        <div class="form-section" style="border-color: #f72585;">
                            <h5 class="section-title" style="color: #f72585;"><i class="fas fa-layer-group"></i> <?= Yii::t('app', 'Content') ?></h5>

                            <?= $form->field($model, 'content_type')->dropDownList([
                                'text' => '📝 ' . Yii::t('app', 'Text Content'),
                                'video' => '🎥 ' . Yii::t('app', 'Video'),
                                'pdf' => '📄 ' . Yii::t('app', 'PDF Document'),
                                'image' => '🖼️ ' . Yii::t('app', 'Image'),
                            ], ['prompt' => Yii::t('app', 'Select Content Type...'), 'id' => 'content-type', 'class' => 'form-select form-glass-control']) ?>

                            <div id="text-content" class="content-toggle" style="display:none;">
                                <?= $form->field($model, 'content')->textarea([
                                    'rows' => 8,
                                    'placeholder' => Yii::t('app', 'Write your lesson content here...')
                                ])->label(Yii::t('app', 'Lesson Content')) ?>
                            </div>

                            <div id="video-content" class="content-toggle" style="display:none;">
                                <?= $form->field($model, 'video_url')->textInput([
                                    'placeholder' => 'https://youtube.com/watch?v=...'
                                ])->label(Yii::t('app', 'Video URL (YouTube)')) ?>

                                <div class="mb-3 p-3 rounded bg-white bg-opacity-10 border border-white border-opacity-10">
                                    <label class="form-label text-white"><?= Yii::t('app', 'OR Upload Video File') ?></label>
                                    <?= $form->field($model, 'file')->fileInput([
                                        'class' => 'form-control form-glass-control',
                                        'accept' => 'video/mp4,video/webm,video/ogg'
                                    ])->label(false) ?>
                                    <p class="field-hint"><?= Yii::t('app', 'Maximum 100MB (MP4, WebM)') ?></p>
                                </div>

                                <?= $form->field($model, 'min_watch_time')->textInput([
                                    'type' => 'number',
                                    'placeholder' => Yii::t('app', 'e.g., 300 (seconds)')
                                ])->label(Yii::t('app', 'Minimum Watch Time (seconds)')) ?>
                            </div>

                            <div id="file-content" class="content-toggle" style="display:none;">
                                <?= $form->field($model, 'file')->fileInput([
                                    'accept' => '.pdf,.jpg,.jpeg,.png'
                                ])->label(Yii::t('app', 'Upload File')) ?>
                                <p class="field-hint"><?= Yii::t('app', 'Supported: PDF, JPG, PNG (Max 50MB)') ?></p>
                            </div>
                        </div>

                        <div class="form-section" style="border-color: #4ade80;">
                            <h5 class="section-title" style="color: #4ade80;"><i class="fas fa-cog"></i> <?= Yii::t('app', 'Settings') ?></h5>

                            <div class="row">
                                <div class="col-md-4">
                                    <?= $form->field($model, 'order_number')->textInput(['type' => 'number', 'min' => 1])->label(Yii::t('app', 'Order #')) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'difficulty_level')->dropDownList([
                                        'easy' => Yii::t('app', 'Easy'),
                                        'medium' => Yii::t('app', 'Medium'),
                                        'hard' => Yii::t('app', 'Hard')
                                    ], ['class' => 'form-select form-glass-control'])->label(Yii::t('app', 'Difficulty')) ?>
                                </div>
                                <div class="col-md-4">
                                    <?= $form->field($model, 'duration_minutes')->textInput(['type' => 'number', 'min' => 1])->label(Yii::t('app', 'Duration (min)')) ?>
                                </div>
                            </div>

                            <div class="form-check form-switch mt-3">
                                <?= $form->field($model, 'is_published')->checkbox([
                                    'class' => 'form-check-input',
                                    'labelOptions' => ['class' => 'form-check-label text-white fw-bold']
                                ])->label(Yii::t('app', 'Publish immediately')) ?>
                            </div>
                        </div>

                        <div class="text-center mt-5">
                            <?= Html::submitButton('<i class="fas fa-save me-2"></i> ' . Yii::t('app', 'Create Lesson'), ['class' => 'btn-create-neon']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contentType = document.getElementById('content-type');
        const textContent = document.getElementById('text-content');
        const videoContent = document.getElementById('video-content');
        const fileContent = document.getElementById('file-content');

        function toggleContent() {
            textContent.style.display = 'none';
            videoContent.style.display = 'none';
            fileContent.style.display = 'none';

            if (contentType.value === 'text') {
                textContent.style.display = 'block';
            } else if (contentType.value === 'video') {
                videoContent.style.display = 'block';
            } else if (contentType.value === 'pdf' || contentType.value === 'image') {
                fileContent.style.display = 'block';
            }
        }

        contentType.addEventListener('change', toggleContent);
        // Initial check if validation failed and page reloaded
        toggleContent();
    });
</script>