<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\TestQuestion;

$this->title = Yii::t('app', 'Edit Question');
?>

<style>
    /* 1. Page Container */
    .edit-question-page {
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
        background: linear-gradient(135deg, #7209b7 0%, #f72585 100%);
        padding: 25px 30px;
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-title h4 {
        font-weight: 800;
        margin: 0;
        font-size: 1.5rem;
        text-shadow: 0 0 15px rgba(247, 37, 133, 0.5);
    }

    /* 4. Form Inputs (Dark Mode) */
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
        border-color: #f72585 !important;
        color: white !important;
        box-shadow: 0 0 0 4px rgba(247, 37, 133, 0.2) !important;
        outline: none;
    }

    .form-label {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    /* 5. Option Groups */
    .option-group-glass {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 5px;
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.3s;
    }
    .option-group-glass:focus-within {
        border-color: #f72585;
        background: rgba(255,255,255,0.08);
    }

    .option-letter {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #7209b7, #f72585);
        color: white;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .option-input-transparent {
        background: transparent;
        border: none;
        color: white;
        flex-grow: 1;
        padding: 10px;
        outline: none;
    }
    .option-input-transparent::placeholder { color: rgba(255,255,255,0.3); }

    .option-check-wrapper {
        padding: 0 10px;
        border-left: 1px solid rgba(255,255,255,0.1);
        display: flex; align-items: center; gap: 5px;
    }

    .neon-check {
        width: 20px; height: 20px;
        cursor: pointer;
        accent-color: #f72585;
    }

    /* 6. Alerts & Sections */
    .info-box-glass {
        background: rgba(247, 37, 133, 0.15);
        border: 1px solid rgba(247, 37, 133, 0.3);
        color: #f9a8d4;
        border-radius: 12px;
        padding: 15px;
        font-size: 0.9rem;
        margin-top: 15px;
    }

    .section-divider {
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 25px 0;
        position: relative;
    }
    .section-label {
        position: absolute;
        top: -12px;
        left: 0;
        background: #1e2532;
        padding-right: 15px;
        color: #f72585;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    /* 7. Buttons */
    .btn-update-neon {
        background: linear-gradient(135deg, #f72585, #b5179e);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(247, 37, 133, 0.4);
        transition: 0.3s;
    }
    .btn-update-neon:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 30px rgba(247, 37, 133, 0.6);
        color: white;
    }

    .btn-add-option {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px dashed rgba(255,255,255,0.3);
        border-radius: 12px;
        padding: 10px 20px;
        width: 100%;
        transition: 0.3s;
    }
    .btn-add-option:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
    }

    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-glass-back:hover { background: white; color: black; }

    .btn-remove-opt {
        color: #f87171;
        background: transparent;
        border: none;
        padding: 5px;
        cursor: pointer;
        opacity: 0.7;
        transition: 0.2s;
    }
    .btn-remove-opt:hover { opacity: 1; transform: scale(1.1); }

</style>

<div class="edit-question-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="glass-card animate__animated animate__fadeInUp">
                    <div class="glass-header">
                        <div class="header-title">
                            <h4><i class="bi bi-pencil-square me-2"></i> <?= Yii::t('app', 'Edit Question') ?></h4>
                            <small class="text-white-50"><?= Html::encode($test->title) ?></small>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <?php $form = ActiveForm::begin(['id' => 'question-form']); ?>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <?= $form->field($model, 'question_type')->dropDownList(
                                    TestQuestion::getTypeOptions(),
                                    [
                                        'class' => 'form-select form-glass-control',
                                        'onchange' => 'changeQuestionType(this.value)'
                                    ]
                                )->label(Yii::t('app', 'Question Type')) ?>
                            </div>
                            <div class="col-md-4">
                                <?= $form->field($model, 'points')->textInput([
                                    'type' => 'number', 'min' => 1,
                                    'class' => 'form-control form-glass-control'
                                ])->label(Yii::t('app', 'Points')) ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <?= $form->field($model, 'question_text')->textarea([
                                'rows' => 3,
                                'class' => 'form-control form-glass-control',
                                'placeholder' => Yii::t('app', 'Enter your question here...')
                            ])->label(Yii::t('app', 'Question Text')) ?>
                        </div>

                        <div id="choice-options" style="display: block;">
                            <div class="section-divider">
                                <span class="section-label"><i class="bi bi-list-ul"></i> <?= Yii::t('app', 'Answer Options') ?></span>
                            </div>
                            
                            <div id="options-container">
                                <?php 
                                $optionCount = count($model->optionsArray ?? []);
                                $optionCount = max($optionCount, 4); // Kamida 4 ta option bo'lishi kerak
                                
                                for ($i = 0; $i < $optionCount; $i++): 
                                ?>
                                    <div class="option-group-glass">
                                        <div class="option-letter"><?= chr(65 + $i) ?></div>
                                        <input type="text" name="TestQuestion[optionsArray][<?= $i ?>]" class="option-input-transparent" 
                                               value="<?= Html::encode($model->optionsArray[$i] ?? '') ?>"
                                               placeholder="<?= Yii::t('app', 'Enter option text...') ?>" required>
                                        <div class="option-check-wrapper">
                                            <input type="checkbox" name="TestQuestion[correctAnswerArray][]" value="<?= $i ?>" 
                                                   class="neon-check correct-answer-checkbox"
                                                   <?= in_array((string)$i, $model->correctAnswerArray ?? []) ? 'checked' : '' ?>>
                                            <span class="small text-white-50"><?= Yii::t('app', 'Correct') ?></span>
                                        </div>
                                        <?php if($i >= 4): // 4 tadan ortiq bo'lsa o'chirish tugmasini ko'rsatish ?>
                                            <button type="button" class="btn-remove-opt" onclick="this.parentElement.remove()">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>

                            <button type="button" class="btn-add-option mt-2" onclick="addOption()">
                                <i class="bi bi-plus-lg"></i> <?= Yii::t('app', 'Add Another Option') ?>
                            </button>

                            <div class="info-box-glass">
                                <i class="bi bi-info-circle me-2"></i>
                                <span id="hint-text"><?= Yii::t('app', 'Select the correct answer(s).') ?></span>
                            </div>
                        </div>

                        <div id="text-answer" style="display: none;">
                            <div class="section-divider">
                                <span class="section-label"><i class="bi bi-keyboard"></i> <?= Yii::t('app', 'Expected Answer') ?></span>
                            </div>
                            
                            <?= Html::textInput('TestQuestion[correctAnswerArray]', is_array($model->correctAnswerArray) ? ($model->correctAnswerArray[0] ?? '') : '', [
                                'class' => 'form-control form-glass-control',
                                'placeholder' => Yii::t('app', 'Enter the correct text answer...'),
                                'id' => 'text-correct-answer'
                            ]) ?>
                            <p class="small text-white-50 mt-2">
                                <i class="bi bi-exclamation-circle me-1"></i> 
                                <?= Yii::t('app', 'Student answer will be compared with this text (case-insensitive).') ?>
                            </p>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <?= Html::a('<i class="bi bi-arrow-left me-2"></i> ' . Yii::t('app', 'Back'), ['manage-questions', 'id' => $test->id], ['class' => 'btn-glass-back']) ?>
                            
                            <?= Html::submitButton('<i class="bi bi-check-lg me-2"></i> ' . Yii::t('app', 'Update Question'), [
                                'class' => 'btn-update-neon',
                                'onclick' => 'return validateBeforeSubmit()'
                            ]) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    let optionCount = <?= $optionCount ?>;

    function changeQuestionType(type) {
        const choiceOptions = document.getElementById('choice-options');
        const textAnswer = document.getElementById('text-answer');
        const checkboxes = document.querySelectorAll('.correct-answer-checkbox');
        const hintText = document.getElementById('hint-text');

        if (type === 'text') {
            choiceOptions.style.display = 'none';
            textAnswer.style.display = 'block';
            
            document.querySelectorAll('#options-container input[type="text"]').forEach(input => input.required = false);
            document.getElementById('text-correct-answer').required = true;
            document.getElementById('text-correct-answer').disabled = false;
            
        } else {
            choiceOptions.style.display = 'block';
            textAnswer.style.display = 'none';

            document.querySelectorAll('#options-container input[type="text"]').forEach(input => input.required = true);
            document.getElementById('text-correct-answer').required = false;
            document.getElementById('text-correct-answer').disabled = true;

            if (type === 'single_choice') {
                hintText.textContent = '<?= Yii::t('app', 'For Single Choice, select only ONE correct answer.') ?>';
                checkboxes.forEach(cb => {
                    cb.type = 'radio';
                    cb.name = 'TestQuestion[correctAnswerArray]'; 
                });
            } else if (type === 'multiple_choice') {
                hintText.textContent = '<?= Yii::t('app', 'For Multiple Choice, select ALL correct answers.') ?>';
                checkboxes.forEach(cb => {
                    cb.type = 'checkbox';
                    cb.name = 'TestQuestion[correctAnswerArray][]'; 
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
        div.className = 'option-group-glass animate__animated animate__fadeIn';
        div.innerHTML = `
            <div class="option-letter">${letter}</div>
            <input type="text" name="TestQuestion[optionsArray][${optionCount}]" class="option-input-transparent" placeholder="<?= Yii::t('app', 'Option') ?> ${letter}" required>
            <div class="option-check-wrapper">
                <input type="${inputType}" name="${inputName}" value="${optionCount}" class="neon-check correct-answer-checkbox">
                <span class="small text-white-50"><?= Yii::t('app', 'Correct') ?></span>
            </div>
            <button type="button" class="btn-remove-opt" onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(div);
        optionCount++;
    }

    function validateBeforeSubmit() {
        const questionType = document.querySelector('select[name="TestQuestion[question_type]"]').value;

        if (questionType === 'multiple_choice' || questionType === 'single_choice') {
            const checked = document.querySelectorAll('.correct-answer-checkbox:checked');
            if (checked.length === 0) {
                alert('<?= Yii::t('app', 'Please select at least one correct answer!') ?>');
                return false;
            }
        }
        return true;
    }

    // Initial Run
    document.addEventListener('DOMContentLoaded', function() {
        const type = document.querySelector('select[name="TestQuestion[question_type]"]').value;
        changeQuestionType(type);
    });
</script>