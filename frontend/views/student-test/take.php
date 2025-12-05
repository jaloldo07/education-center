<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\TestQuestion;

$this->title = 'Taking Test: ' . $test->title;
?>

<style>

    /* ⏰ Sticky Timer Header */
    .timer-header {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.15);
        padding: 1.5rem;
        position: sticky;
        top: 10px;
        z-index: 1000;
        margin-bottom: 2rem;
    }

    .timer-header h5 {
        color: #414fde;
        font-weight: 700;
        margin: 0;
    }

    /* 📊 Progress Bar */
    .progress {
        height: 20px;
        border-radius: 10px;
        background: #efefff;
        overflow: hidden;
    }

    .progress-bar {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        border-radius: 10px;
        transition: width 0.3s ease;
        font-weight: 700;
        font-size: 0.9rem;
    }

    /* ⏱️ Timer Badge */
    .timer-badge {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 1.3rem;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
        display: inline-block;
    }

    .timer-badge.warning {
        background: linear-gradient(135deg, #ff9800, #f57c00);
    }

    .timer-badge.danger {
        background: linear-gradient(135deg, #212121, #424242);
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    /* 📝 Question Cards */
    .question-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        border-color: #414fde;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.15);
    }

    .question-header {
        background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);
        color: white;
        padding: 1.25rem;
    }

    .question-header h5 {
        margin: 0;
        font-weight: 700;
    }

    .question-body {
        padding: 2rem;
    }

    .question-text {
        font-size: 1.15rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 1.5rem;
    }

    /* 🏷️ Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
    }

    /* ✅ Form Options */
    .form-check {
        background: #f8f9ff;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 0.75rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .form-check:hover {
        background: #efefff;
        border-color: #414fde;
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

    .form-check-input[type="radio"] {
        border-radius: 50%;
    }

    .form-check-label {
        font-weight: 500;
        color: #333;
        cursor: pointer;
        margin-left: 0.5rem;
    }

    /* 📝 Textarea */
    textarea.form-control {
        border-radius: 12px;
        border: 2px solid #efefff;
        padding: 1rem;
        min-height: 120px;
        transition: all 0.3s ease;
    }

    textarea.form-control:focus {
        border-color: #414fde;
        box-shadow: 0 0 0 0.2rem rgba(65, 79, 222, 0.15);
    }

    /* 🔔 Alert */
    .alert-info {
        background: linear-gradient(135deg, rgba(65, 79, 222, 0.1), rgba(107, 116, 255, 0.1));
        border: 2px solid #414fde;
        border-radius: 12px;
        color: #414fde;
        font-weight: 600;
    }

    /* 🔘 Submit Section */
    .submit-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.2);
        border: 3px solid #4caf50;
        padding: 2.5rem;
        text-align: center;
    }

    .submit-card h5 {
        color: #4caf50;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
        font-size: 1.1rem;
        padding: 14px 32px;
    }

    .btn-success:hover {
        background: linear-gradient(135deg, #45a049, #388e3c) !important;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.5);
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .timer-header {
            padding: 1rem;
        }
        .timer-badge {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .question-body {
            padding: 1.5rem;
        }
    }
</style>

<div class="container-fluid py-3">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Timer & Progress Bar -->
            <div class="timer-header">
                <div class="row align-items-center g-3">
                    <div class="col-md-4">
                        <h5><i class="bi bi-file-text"></i> <?= Html::encode($test->title) ?></h5>
                    </div>
                    <div class="col-md-4">
                        <div class="progress">
                            <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                <span id="progress-text">0 / <?= count($questions) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <span class="timer-badge" id="timer-badge">
                            <i class="bi bi-clock"></i> <span id="time-display"><?= floor($timeRemaining / 60) ?>:<?= str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Questions Form -->
            <?= Html::beginForm(['submit', 'id' => $attempt->id], 'post', ['id' => 'test-form']) ?>
                
                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-card" data-question="<?= $index ?>">
                        <div class="question-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>
                                    <span class="badge bg-primary">Question <?= $index + 1 ?></span>
                                    <span class="badge bg-secondary"><?= $question->points ?> point<?= $question->points > 1 ? 's' : '' ?></span>
                                </h5>
                                <span class="badge bg-info">
                                    <i class="bi bi-<?= $question->question_type === TestQuestion::TYPE_TEXT ? 'keyboard' : 'check-square' ?>"></i>
                                    <?= TestQuestion::getTypeOptions()[$question->question_type] ?>
                                </span>
                            </div>
                        </div>
                        <div class="question-body">
                            <p class="question-text"><?= Html::encode($question->question_text) ?></p>

                            <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE): ?>
                                <!-- Single Choice -->
                                <?php foreach ($question->optionsArray as $optIndex => $option): ?>
                                    <div class="form-check">
                                        <input class="form-check-input answer-input" 
                                               type="radio" 
                                               name="answers[<?= $question->id ?>]" 
                                               value="<?= $optIndex ?>" 
                                               id="q<?= $question->id ?>_opt<?= $optIndex ?>"
                                               data-question="<?= $index ?>">
                                        <label class="form-check-label" for="q<?= $question->id ?>_opt<?= $optIndex ?>">
                                            <strong><?= chr(65 + $optIndex) ?>.</strong> <?= Html::encode($option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                            <?php elseif ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>
                                <!-- Multiple Choice -->
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle"></i> <strong>Select all correct answers</strong>
                                </div>
                                <?php foreach ($question->optionsArray as $optIndex => $option): ?>
                                    <div class="form-check">
                                        <input class="form-check-input answer-input" 
                                               type="checkbox" 
                                               name="answers[<?= $question->id ?>][]" 
                                               value="<?= $optIndex ?>" 
                                               id="q<?= $question->id ?>_opt<?= $optIndex ?>"
                                               data-question="<?= $index ?>">
                                        <label class="form-check-label" for="q<?= $question->id ?>_opt<?= $optIndex ?>">
                                            <strong><?= chr(65 + $optIndex) ?>.</strong> <?= Html::encode($option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>
                                <!-- Text Answer -->
                                <textarea name="answers[<?= $question->id ?>]" 
                                          class="form-control answer-input" 
                                          rows="4" 
                                          placeholder="Type your answer here..."
                                          data-question="<?= $index ?>"></textarea>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Submit Button -->
                <div class="submit-card">
                    <h5><i class="bi bi-check-circle"></i> Ready to submit your test?</h5>
                    <p class="text-muted mb-3">Make sure you've answered all questions!</p>
                    <?= Html::submitButton('<i class="bi bi-send-fill"></i> Submit Test', [
                        'class' => 'btn btn-success',
                        'id' => 'submit-btn',
                        'onclick' => 'return confirmSubmit()'
                    ]) ?>
                </div>

            <?= Html::endForm() ?>
        </div>
    </div>
</div>

<script>
// Timer
let timeRemaining = <?= $timeRemaining ?>; // seconds
const timerDisplay = document.getElementById('time-display');
const timerBadge = document.getElementById('timer-badge');

function updateTimer() {
    if (timeRemaining <= 0) {
        alert('⏰ Time is up! Submitting your test...');
        document.getElementById('test-form').submit();
        return;
    }
    
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    timerDisplay.textContent = minutes + ':' + seconds.toString().padStart(2, '0');
    
    // Warning colors
    if (timeRemaining <= 60) {
        timerBadge.classList.remove('warning');
        timerBadge.classList.add('danger');
    } else if (timeRemaining <= 300) {
        timerBadge.classList.add('warning');
    }
    
    timeRemaining--;
}

// Start timer
updateTimer();
const timerInterval = setInterval(updateTimer, 1000);

// Progress tracking
const answerInputs = document.querySelectorAll('.answer-input');
const progressBar = document.getElementById('progress-bar');
const progressText = document.getElementById('progress-text');
const totalQuestions = <?= count($questions) ?>;
let answeredQuestions = new Set();

answerInputs.forEach(input => {
    input.addEventListener('change', function() {
        const questionIndex = this.getAttribute('data-question');
        answeredQuestions.add(questionIndex);
        updateProgress();
    });
});

function updateProgress() {
    const answered = answeredQuestions.size;
    const percentage = Math.round((answered / totalQuestions) * 100);
    progressBar.style.width = percentage + '%';
    progressBar.setAttribute('aria-valuenow', percentage);
    progressText.textContent = answered + ' / ' + totalQuestions;
}

function confirmSubmit() {
    clearInterval(timerInterval);
    const answered = answeredQuestions.size;
    
    if (answered < totalQuestions) {
        return confirm('⚠️ You have only answered ' + answered + ' out of ' + totalQuestions + ' questions.\n\nSubmit anyway?');
    }
    
    return confirm('✅ Are you sure you want to submit your test?');
}

// Prevent accidental page close
window.addEventListener('beforeunload', function(e) {
    e.preventDefault();
    e.returnValue = 'You have an active test. Are you sure you want to leave?';
});
</script>