<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TestQuestion;

$this->title = 'Add Question';
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .add-question-page {
        animation: fadeSlide 0.6s ease;
    }

    /* 🔥 Page Card */
    .page-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.1);
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        padding: 2rem;
    }

    .card-header-custom h4 {
        font-weight: 700;
        margin: 0;
    }

    .card-body-custom {
        padding: 2.5rem;
    }

    /* 🎯 Form Elements */
    .form-label {
        font-weight: 600;
        color: #414fde;
        margin-bottom: 0.75rem;
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

    /* 📋 Options Section */
    #choice-options {
        background: #f8f9ff;
        padding: 1.5rem;
        border-radius: 16px;
        margin-top: 1.5rem;
    }

    #choice-options h5 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .input-group {
        margin-bottom: 1rem;
    }

    .input-group-text {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border: none;
        border-radius: 12px 0 0 12px !important;
        font-weight: 700;
        min-width: 50px;
        justify-content: center;
    }

    .input-group .form-control {
        border-radius: 0 !important;
        border-left: none;
    }

    .input-group .input-group-text:last-child {
        border-radius: 0 12px 12px 0 !important;
        background: white;
        border: 2px solid #efefff;
        border-left: none;
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

    /* 📌 Text Answer Section */
    #text-answer {
        background: #f8f9ff;
        padding: 1.5rem;
        border-radius: 16px;
        margin-top: 1.5rem;
    }

    #text-answer h5 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* 🔔 Alert */
    .alert-info {
        background: linear-gradient(135deg, rgba(65, 79, 222, 0.1), rgba(107, 116, 255, 0.1));
        border: 2px solid #414fde;
        border-radius: 12px;
        color: #414fde;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #6c757d;
        color: #6c757d;
        background: white;
    }

    .btn-outline-secondary:hover {
        background: #6c757d;
        color: white;
    }

    .btn-outline-danger {
        border: 2px solid #f44336;
        color: #f44336;
        background: white;
    }

    .btn-outline-danger:hover {
        background: #f44336;
        color: white;
    }

    .btn-lg {
        padding: 14px 28px;
        font-size: 1.1rem;
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
        .card-body-custom {
            padding: 1.5rem;
        }
    }
</style>

<div class="add-question-page container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="page-card">
                <div class="card-header-custom">
                    <h4><i class="bi bi-plus-circle"></i> Add Question</h4>
                    <small>Test: <?= Html::encode($test->title) ?></small>
                </div>
                <div class="card-body-custom">
                    <?php $form = ActiveForm::begin(['id' => 'question-form']); ?>

                    <div class="row">
                        <div class="col-md-8">
                            <?= $form->field($model, 'question_type')->dropDownList(
                                TestQuestion::getTypeOptions(),
                                [
                                    'class' => 'form-select',
                                    'onchange' => 'changeQuestionType(this.value)'
                                ]
                            ) ?>
                        </div>
                        <div class="col-md-4">
                            <?= $form->field($model, 'points')->textInput([
                                'type' => 'number',
                                'min' => 1,
                                'value' => 1
                            ]) ?>
                        </div>

                        <div class="col-12">
                            <?= $form->field($model, 'question_text')->textarea([
                                'rows' => 3,
                                'placeholder' => 'Enter your question here...',
                                'class' => 'form-control'
                            ])->label('Question') ?>
                        </div>
                    </div>

                    <!-- Single Choice / Multiple Choice Options -->
                    <div id="choice-options" style="display: block;">
                        <hr>
                        <h5>Answer Options</h5>
                        <div id="options-container">
                            <?php for ($i = 0; $i < 4; $i++): ?>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><?= chr(65 + $i) ?></span>
                                    <input type="text" name="TestQuestion[optionsArray][<?= $i ?>]" class="form-control" placeholder="Option <?= chr(65 + $i) ?>" required>
                                    <div class="input-group-text">
                                        <input type="checkbox" name="TestQuestion[correctAnswerArray][]" value="<?= $i ?>" class="form-check-input correct-answer-checkbox">
                                        <small class="ms-2">Correct</small>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOption()">
                            <i class="bi bi-plus"></i> Add More Options
                        </button>
                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i> <strong>Single Choice:</strong> Check ONE correct answer.<br>
                            <i class="bi bi-info-circle"></i> <strong>Multiple Choice:</strong> Check ALL correct answers.
                        </div>
                    </div>

                    <!-- Text Answer -->
                    <div id="text-answer" style="display: none;">
                        <hr>
                        <h5>Expected Answer</h5>
                        <?= Html::textInput('TestQuestion[correctAnswerArray]', '', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter the correct answer...',
                            'id' => 'text-correct-answer'
                        ]) ?>
                        <small class="text-muted">Student answers will be compared with this (case-insensitive)</small>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <?= Html::a('<i class="bi bi-arrow-left"></i> Back', ['manage-questions', 'id' => $test->id], ['class' => 'btn btn-secondary']) ?>
                        <?= Html::submitButton('<i class="bi bi-check-circle"></i> Save Question', ['class' => 'btn btn-success btn-lg']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let optionCount = 4;

    function changeQuestionType(type) {
        const choiceOptions = document.getElementById('choice-options');
        const textAnswer = document.getElementById('text-answer');
        const checkboxes = document.querySelectorAll('.correct-answer-checkbox');

        console.log('Checkboxes set!', checkboxes.length);

        if (type === 'text') {
            choiceOptions.style.display = 'none';
            textAnswer.style.display = 'block';

            document.querySelectorAll('#options-container input[type="text"]').forEach(input => {
                input.required = false;
            });
            document.getElementById('text-correct-answer').required = true;
            document.getElementById('text-correct-answer').disabled = false; // Enable text input
            
            // Disable checkboxes when not in use
            checkboxes.forEach(cb => cb.disabled = true);
        } else {
            choiceOptions.style.display = 'block';
            textAnswer.style.display = 'none';

            document.querySelectorAll('#options-container input[type="text"]').forEach(input => {
                input.required = true;
            });
            document.getElementById('text-correct-answer').required = false;
            document.getElementById('text-correct-answer').disabled = true; // Disable text input
            
            // Enable checkboxes
            checkboxes.forEach(cb => cb.disabled = false);

            // Change input type based on question type
            if (type === 'single_choice') {
                checkboxes.forEach(cb => {
                    cb.type = 'radio';
                    cb.name = 'TestQuestion[correctAnswerArray]';
                    cb.checked = false; // Reset
                });
            } else if (type === 'multiple_choice') {
                checkboxes.forEach(cb => {
                    cb.type = 'checkbox';
                    cb.name = 'TestQuestion[correctAnswerArray][]';
                    cb.checked = false; // Reset
                });
            }
        }
    }

    function addOption() {
        const container = document.getElementById('options-container');
        const letter = String.fromCharCode(65 + optionCount);
        const type = document.querySelector('select[name="TestQuestion[question_type]"]').value;
        const inputType = type === 'single_choice' ? 'radio' : 'checkbox';
        const inputName = type === 'single_choice' ? 'TestQuestion[correctAnswerArray]' : 'TestQuestion[correctAnswerArray][]';

        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <span class="input-group-text">${letter}</span>
        <input type="text" name="TestQuestion[optionsArray][${optionCount}]" class="form-control" placeholder="Option ${letter}" required>
        <div class="input-group-text">
            <input type="${inputType}" name="${inputName}" value="${optionCount}" class="form-check-input correct-answer-checkbox">
            <small class="ms-2">Correct</small>
        </div>
        <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bi bi-trash"></i>
        </button>
    `;
        container.appendChild(div);
        optionCount++;
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const questionType = document.querySelector('select[name="TestQuestion[question_type]"]').value;
        changeQuestionType(questionType);
    });
</script>