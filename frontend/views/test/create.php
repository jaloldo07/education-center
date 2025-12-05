<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Course;
use common\models\Group;

$this->title = 'Create New Test';
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .create-test-page {
        animation: fadeSlide 0.6s ease;
    }

    /* 🔥 Page Header */
    .page-header {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.2);
    }

    .page-header h2 {
        font-weight: 700;
        margin: 0;
    }

    /* 📝 Form Card */
    .form-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.1);
        padding: 2.5rem;
        margin-bottom: 2rem;
    }

    .form-card h4 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #efefff;
    }

    /* 🎯 Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #414fde;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 12px;
        border: 2px solid #efefff;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #414fde;
        box-shadow: 0 0 0 0.2rem rgba(65, 79, 222, 0.15);
    }

    textarea.form-control {
        min-height: 120px;
    }

    /* 📋 Form Text */
    .form-text {
        color: #6c6e8a;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* ✅ Checkbox */
    .form-check {
        background: #f8f9ff;
        padding: 1rem;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .form-check-input {
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #414fde;
        border-radius: 6px;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #414fde;
        border-color: #414fde;
    }

    .form-check-label {
        margin-left: 0.5rem;
        font-weight: 600;
        color: #414fde;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #333dcc, #5563ff) !important;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
    }

    .btn-secondary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(108, 117, 125, 0.4);
    }

    .btn-lg {
        padding: 14px 28px;
        font-size: 1.1rem;
    }

    /* 🏷️ Badge/Status */
    .badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* 📱 Row */
    .row {
        margin-bottom: 1rem;
    }

    /* ✨ Animations */
    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .form-card {
            padding: 1.5rem;
        }
        .page-header {
            padding: 1.5rem;
        }
    }
</style>

<div class="create-test-page container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <h2><i class="bi bi-plus-circle"></i> Create New Test</h2>
        <p class="mb-0">Fill in the form below to create a new test for your students</p>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'test-form']
        ]); ?>

        <!-- Basic Information -->
        <h4><i class="bi bi-info-circle"></i> Basic Information</h4>
        
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'title')->textInput([
                    'placeholder' => 'e.g., Midterm Exam - Mathematics',
                    'class' => 'form-control'
                ])->label('Test Title <span class="text-danger">*</span>') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'status')->dropDownList([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'closed' => 'Closed'
                ], ['class' => 'form-select'])->label('Status <span class="text-danger">*</span>') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'course_id')->dropDownList(
                    ArrayHelper::map(Course::find()->all(), 'id', 'name'),
                    ['prompt' => 'Select Course', 'class' => 'form-select']
                )->label('Course <span class="text-danger">*</span>') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'group_id')->dropDownList(
                    ArrayHelper::map(Group::find()->all(), 'id', 'name'),
                    ['prompt' => 'Select Group (Optional)', 'class' => 'form-select']
                )->label('Group')->hint('Leave empty for all students in the course') ?>
            </div>
        </div>

        <?= $form->field($model, 'description')->textarea([
            'rows' => 4,
            'placeholder' => 'Describe the test objectives, topics covered, and any special instructions...',
            'class' => 'form-control'
        ])->label('Description')->hint('Optional: Provide additional context for students') ?>

        <!-- Test Settings -->
        <h4 class="mt-4"><i class="bi bi-gear"></i> Test Settings</h4>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'duration')->textInput([
                    'type' => 'number',
                    'min' => 1,
                    'placeholder' => '60',
                    'class' => 'form-control'
                ])->label('Duration (minutes) <span class="text-danger">*</span>')->hint('How long students have to complete the test') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'passing_score')->textInput([
                    'type' => 'number',
                    'min' => 0,
                    'max' => 100,
                    'placeholder' => '60',
                    'class' => 'form-control'
                ])->label('Passing Score (%) <span class="text-danger">*</span>')->hint('Minimum score to pass') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'require_face_control')->checkbox([
                    'label' => 'Require Face Control',
                    'class' => 'form-check-input'
                ])->hint('Students must take a photo before starting')->label(false) ?>
            </div>
        </div>

        <!-- Schedule -->
        <h4 class="mt-4"><i class="bi bi-calendar"></i> Schedule (Optional)</h4>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'start_date')->input('datetime-local', [
                    'class' => 'form-control'
                ])->label('Start Date & Time')->hint('When students can start taking the test') ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'end_date')->input('datetime-local', [
                    'class' => 'form-control'
                ])->label('End Date & Time')->hint('Deadline for test submission') ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between mt-4">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::submitButton('<i class="bi bi-check-circle"></i> Create Test', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>