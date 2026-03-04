<?php

use yii\helpers\Html;
use common\models\TestQuestion;

/* @var $this yii\web\View */
/* @var $attempt common\models\TestAttempt */
/* @var $test common\models\Test */
/* @var $questions common\models\TestQuestion[] */
/* @var $timeRemaining int */

$this->title = Yii::t('app', 'Taking Test') . ': ' . $test->title;
?>

<style>
    /* 1. Page Container */
    .take-test-page {
        padding: 20px 0 60px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Sticky Header (Timer & Progress) */
    .timer-glass-header {
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 15px 25px;
        position: sticky;
        top: 20px;
        z-index: 1000;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .test-info h5 {
        margin: 0;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Progress Bar */
    .progress-container {
        flex-grow: 1;
        margin: 0 30px;
        position: relative;
    }

    .progress-glass {
        height: 12px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-bar-neon {
        height: 100%;
        background: linear-gradient(90deg, #4361ee, #f72585);
        width: 0%;
        transition: width 0.3s ease;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .progress-text {
        position: absolute;
        top: -20px;
        right: 0;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.6);
    }

    /* Timer Badge */
    .timer-neon-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 1.2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 130px;
        justify-content: center;
        transition: 0.3s;
    }

    .timer-neon-badge.warning {
        border-color: #fbbf24;
        color: #fbbf24;
        box-shadow: 0 0 15px rgba(251, 191, 36, 0.2);
    }

    .timer-neon-badge.danger {
        border-color: #f87171;
        color: #f87171;
        box-shadow: 0 0 15px rgba(248, 113, 113, 0.3);
        animation: pulseRed 1s infinite;
    }

    @keyframes pulseRed {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    /* 3. Question Cards */
    .question-glass-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        margin-bottom: 30px;
        overflow: hidden;
        transition: 0.3s;
    }

    .question-glass-card:hover {
        border-color: rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.05);
    }

    .q-header {
        padding: 20px 30px;
        background: rgba(255, 255, 255, 0.03);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .q-number {
        font-weight: 800;
        color: #4cc9f0;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .q-points {
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .q-body {
        padding: 30px;
    }

    .q-text {
        font-size: 1.2rem;
        color: white;
        margin-bottom: 25px;
        font-weight: 600;
        line-height: 1.6;
    }

    /* 4. Options (Custom Radio/Checkbox) */
    .option-wrapper {
        margin-bottom: 15px;
        position: relative;
    }

    .option-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .option-label {
        display: flex;
        align-items: center;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        color: rgba(255, 255, 255, 0.8);
        font-size: 1rem;
    }

    .option-label:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .option-marker {
        width: 24px;
        height: 24px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        /* Radio by default */
        margin-right: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        flex-shrink: 0;
    }

    /* Checkbox square style */
    .option-input[type="checkbox"]+.option-label .option-marker {
        border-radius: 6px;
    }

    .option-marker::after {
        content: '';
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
        /* Radio */
        opacity: 0;
        transform: scale(0);
        transition: 0.2s;
    }

    .option-input[type="checkbox"]+.option-label .option-marker::after {
        border-radius: 2px;
        /* Checkbox */
    }

    /* Checked State */
    .option-input:checked+.option-label {
        background: rgba(67, 97, 238, 0.2);
        border-color: #4361ee;
        color: white;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.2);
    }

    .option-input:checked+.option-label .option-marker {
        border-color: #4361ee;
        background: #4361ee;
    }

    .option-input:checked+.option-label .option-marker::after {
        opacity: 1;
        transform: scale(1);
    }

    /* Textarea */
    .glass-textarea {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        border-radius: 12px;
        padding: 15px;
        width: 100%;
        resize: vertical;
        font-family: inherit;
    }

    .glass-textarea:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 10px rgba(67, 97, 238, 0.3);
    }

    /* 5. Submit Section */
    .submit-section {
        background: rgba(15, 23, 42, 0.7);
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 40px;
    }

    .btn-finish-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 15px 50px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 1.2rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
    }

    .btn-finish-neon:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 35px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .timer-glass-header {
            flex-direction: column;
            align-items: stretch;
            top: 0;
        }

        .progress-container {
            margin: 10px 0;
        }

        .timer-neon-badge {
            width: 100%;
        }
    }
</style>

<div class="take-test-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="timer-glass-header animate__animated animate__fadeInDown">
                    <div class="test-info">
                        <h5><i class="bi bi-file-earmark-text me-2"></i> <?= Html::encode($test->title) ?></h5>
                    </div>

                    <div class="progress-container">
                        <div class="progress-text"><span id="answered-count">0</span> / <?= count($questions) ?></div>
                        <div class="progress-glass">
                            <div id="progress-bar" class="progress-bar-neon"></div>
                        </div>
                    </div>

                    <div class="timer-neon-badge" id="timer-badge">
                        <i class="bi bi-stopwatch"></i>
                        <span id="time-display"><?= floor($timeRemaining / 60) ?>:<?= str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) ?></span>
                    </div>
                </div>

                <?= Html::beginForm(['submit', 'id' => $attempt->id], 'post', ['id' => 'test-form']) ?>

                <?php foreach ($questions as $index => $question): ?>
                    <div class="question-glass-card animate__animated animate__fadeInUp" data-question="<?= $index ?>">
                        <div class="q-header">
                            <div class="q-number">
                                <i class="bi bi-question-circle-fill"></i> <?= Yii::t('app', 'Question') ?> <?= $index + 1 ?>
                            </div>
                            <div class="q-points">
                                <?= $question->points ?> pts
                            </div>
                        </div>

                        <div class="q-body">
                            <div class="q-text">
                                <?= Html::encode($question->question_text) ?>
                            </div>

                            <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE): ?>

                                <?php foreach ($question->optionsArray as $key => $option): ?>
                                    <div class="option-wrapper">
                                        <input class="option-input answer-input"
                                            type="radio"
                                            name="answers[<?= $question->id ?>]"
                                            value="<?= $key ?>"
                                            id="q<?= $question->id ?>_opt<?= $key ?>"
                                            data-question="<?= $index ?>">
                                        <label class="option-label" for="q<?= $question->id ?>_opt<?= $key ?>">
                                            <span class="option-marker"></span>
                                            <?= Html::encode($option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                            <?php elseif ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>

                                <p class="text-white-50 small mb-3"><i class="bi bi-check-all me-1"></i> <?= Yii::t('app', 'Select all correct answers') ?></p>
                                <?php foreach ($question->optionsArray as $key => $option): ?>
                                    <div class="option-wrapper">
                                        <input class="option-input answer-input"
                                            type="checkbox"
                                            name="answers[<?= $question->id ?>][]"
                                            value="<?= $key ?>"
                                            id="q<?= $question->id ?>_opt<?= $key ?>"
                                            data-question="<?= $index ?>">
                                        <label class="option-label" for="q<?= $question->id ?>_opt<?= $key ?>">
                                            <span class="option-marker"></span>
                                            <?= Html::encode($option) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="form-group">
                                    <textarea name="answers[<?= $question->id ?>]"
                                        class="glass-textarea answer-input"
                                        rows="5"
                                        placeholder="<?= Yii::t('app', 'Type your answer here...') ?>"
                                        data-question="<?= $index ?>"></textarea>
                                </div>

                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="submit-section animate__animated animate__fadeInUp">
                    <h4 class="text-white mb-3"><?= Yii::t('app', 'All set?') ?></h4>
                    <p class="text-white-50 mb-4"><?= Yii::t('app', 'Review your answers before submitting. Good luck!') ?></p>
                    <?= Html::button('<i class="bi bi-send-check me-2"></i> ' . Yii::t('app', 'Submit Test'), [
                        'class' => 'btn-finish-neon',
                        'id' => 'custom-submit-btn'
                    ]) ?>
                </div>

                <?= Html::endForm() ?>

            </div>
        </div>
    </div>
</div>


<style>
    .test-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .test-modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .test-modal-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 40px 30px;
        text-align: center;
        color: white;
        max-width: 400px;
        width: 90%;
        transform: scale(0.8);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
    }

    .test-modal-overlay.active .test-modal-box {
        transform: scale(1);
    }

    .test-modal-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        text-shadow: 0 0 20px currentColor;
    }
    .glass-textarea {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        color: #fff !important;
        padding: 15px;
        width: 100%;
        pointer-events: auto !important; /* 🔥 Asosiy yechim: Bosishga ruxsat berish */
        position: relative;
        z-index: 50; /* Boshqa qatlamlar to'sib qo'ymasligi uchun */
    }
    .glass-textarea:focus {
        background: rgba(255, 255, 255, 0.1);
        outline: none;
        border-color: #4cc9f0;
        box-shadow: 0 0 15px rgba(76, 201, 240, 0.3);
    }
</style>

<div id="custom-test-modal" class="test-modal-overlay">
    <div class="test-modal-box">
        <div id="modal-icon" class="test-modal-icon"></div>
        <h4 id="modal-title" class="mb-3 fw-bold"></h4>
        <p id="modal-message" class="text-white-50 mb-4" style="line-height: 1.6;"></p>
        <div id="modal-buttons" class="d-flex justify-content-center gap-3">
            <button class="btn btn-secondary px-4 rounded-pill" onclick="closeTestModal()"><?= Yii::t('app', 'Bekor qilish') ?></button>
            <button class="btn btn-primary px-4 rounded-pill" id="modal-confirm-btn"><?= Yii::t('app', 'Tasdiqlash') ?></button>
        </div>
    </div>
</div>

<script>
// Modal boshqaruvi
const modalOverlay = document.getElementById('custom-test-modal');
const modalIcon = document.getElementById('modal-icon');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const modalBtns = document.getElementById('modal-buttons');
const confirmBtn = document.getElementById('modal-confirm-btn');

function showTestModal(icon, iconClass, title, message, showCancel = true, confirmAction = null) {
    modalIcon.innerHTML = `<i class="${icon}"></i>`;
    modalIcon.className = `test-modal-icon ${iconClass}`;
    modalTitle.textContent = title;
    modalMessage.innerHTML = message;
    
    if (showCancel) {
        modalBtns.style.display = 'flex';
        confirmBtn.onclick = confirmAction;
    } else {
        modalBtns.style.display = 'none'; // Vaqt tugaganda tugmalar kerak emas
    }
    modalOverlay.classList.add('active');
}

function closeTestModal() {
    modalOverlay.classList.remove('active');
}

let isSubmitting = false;

function forceSubmit() {
    isSubmitting = true; // Jo'natishni boshladik, brauzerga jim turishni aytamiz
    document.getElementById('test-form').submit();
}

// Sahifadan tasodifan chiqib ketishni tasdiqlash
window.addEventListener('beforeunload', function(e) {
    if (!isSubmitting) { // 🔥 Agar forma jo'natilmayotgan bo'lsagina brauzer alerti chiqsin
        e.preventDefault(); 
        e.returnValue = '';
    }
});

// Vaqt mantig'i (Timer logic)
let timeRemaining = <?= $timeRemaining ?>;
const timerDisplay = document.getElementById('time-display');
const timerBadge = document.getElementById('timer-badge');

function updateTimer() {
    if (timeRemaining <= 0) {
        // Eski xunuk alert o'rniga:
        showTestModal('bi bi-alarm', 'text-danger', '<?= Yii::t('app', 'Vaqt tugadi!') ?>', '<?= Yii::t('app', 'Test avtomatik ravishda yakunlanmoqda...') ?>', false);
        setTimeout(forceSubmit, 2000); // 2 soniyadan keyin avtomat jo'natadi
        return;
    }
    
    const minutes = Math.floor(timeRemaining / 60);
    const seconds = timeRemaining % 60;
    timerDisplay.textContent = minutes + ':' + seconds.toString().padStart(2, '0');
    
    if (timeRemaining <= 60) {
        timerBadge.className = 'timer-neon-badge danger';
    } else if (timeRemaining <= 300) {
        timerBadge.className = 'timer-neon-badge warning';
    }
    timeRemaining--;
}

setInterval(updateTimer, 1000);
updateTimer();

// Javoblarni kuzatish (Progress Tracking)
const answerInputs = document.querySelectorAll('.answer-input');
const progressBar = document.getElementById('progress-bar');
const answeredCountEl = document.getElementById('answered-count');
const totalQuestions = <?= count($questions) ?>;
let answeredSet = new Set();

answerInputs.forEach(input => {
    // 🔥 Textarea uchun har bir harf yozganda (input), boshqalar uchun belgilaganda (change) ishlaydi
    let eventType = input.tagName === 'TEXTAREA' ? 'input' : 'change';

    input.addEventListener(eventType, () => {
        if (input.tagName === 'TEXTAREA') {
            if (input.value.trim().length > 0) {
                answeredSet.add(input.getAttribute('data-question'));
            } else {
                answeredSet.delete(input.getAttribute('data-question'));
            }
        } else {
            answeredSet.add(input.getAttribute('data-question'));
        }
        updateProgress();
    });
    
    // Sahifa yuklanganda avvaldan yozilgan matn bo'lsa tekshirish
    if (input.tagName === 'TEXTAREA' && input.value.trim().length > 0) {
        answeredSet.add(input.getAttribute('data-question'));
    }
});

function updateProgress() {
    const count = answeredSet.size;
    progressBar.style.width = Math.round((count / totalQuestions) * 100) + '%';
    answeredCountEl.textContent = count;
}

// Eski confirm() o'rniga Custom Modalni chaqirish
document.getElementById('custom-submit-btn').addEventListener('click', function() {
    const answered = answeredSet.size;
    
    if (answered < totalQuestions) {
        showTestModal(
            'bi bi-exclamation-triangle-fill', 'text-warning',
            '<?= Yii::t('app', 'Diqqat!') ?>',
            '<?= Yii::t('app', 'Sizda belgilanmagan savollar bor.<br>Haqiqatan ham testni yakunlamoqchimisiz?') ?>',
            true, forceSubmit
        );
    } else {
        showTestModal(
            'bi bi-check-circle-fill', 'text-success',
            '<?= Yii::t('app', 'Ajoyib!') ?>',
            '<?= Yii::t('app', 'Barcha savollarga javob berdingiz.<br>Testni yakunlaysizmi?') ?>',
            true, forceSubmit
        );
    }
});

// Sahifadan chiqib ketishni tasdiqlash
window.addEventListener('beforeunload', function(e) {
    e.preventDefault(); 
    e.returnValue = '';
});
</script>