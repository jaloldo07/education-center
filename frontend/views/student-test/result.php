<?php
use yii\helpers\Html;
use common\models\TestQuestion;

/* @var $this yii\web\View */
/* @var $attempt common\models\TestAttempt */
/* @var $answers common\models\TestAnswer[] */

$this->title = Yii::t('app', 'Test Result');

// 🔥 FIX: Ballni qayta hisoblash EMAS, bazadagi "is_correct" ga qarab hisoblaymiz.
$recalculatedScore = 0;
$totalPossible = 0;

foreach ($answers as $ans) {
    $q = $ans->question;
    $totalPossible += $q->points;
    
    // Agar javob bazada to'g'ri deb saqlangan bo'lsa, ball qo'shamiz
    if ($ans->is_correct) {
        $recalculatedScore += $q->points;
    }
}

// Foizni hisoblash
$percentage = ($totalPossible > 0) ? round(($recalculatedScore / $totalPossible) * 100) : 0;
$passed = $percentage >= $attempt->test->passing_score;
$statusClass = $passed ? 'success' : 'danger';
?>

<style>
    /* 1. Page Container */
    .result-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }

    /* 2. Hero Result Card */
    .result-hero-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 30px;
        padding: 40px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .result-hero-card.glow-success { border-color: rgba(74, 222, 128, 0.3); box-shadow: 0 0 50px rgba(74, 222, 128, 0.1); }
    .result-hero-card.glow-danger { border-color: rgba(248, 113, 113, 0.3); box-shadow: 0 0 50px rgba(248, 113, 113, 0.1); }

    /* Score Circle */
    .score-circle {
        width: 180px; height: 180px;
        border-radius: 50%;
        border: 8px solid;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px auto;
        font-size: 3.5rem; font-weight: 800;
        background: rgba(255,255,255,0.03);
    }
    .score-circle.success { border-color: #4ade80; color: #4ade80; text-shadow: 0 0 20px rgba(74, 222, 128, 0.5); }
    .score-circle.danger { border-color: #f87171; color: #f87171; text-shadow: 0 0 20px rgba(248, 113, 113, 0.5); }

    .result-title { font-size: 2rem; font-weight: 800; margin-bottom: 10px; color: white; }
    .result-message { color: rgba(255,255,255,0.7); font-size: 1.1rem; }

    /* 3. Stats Grid */
    .stat-glass-box {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px; padding: 20px; text-align: center;
        height: 100%; display: flex; flex-direction: column; justify-content: center;
    }
    .stat-val { font-size: 1.5rem; font-weight: 700; color: white; margin-bottom: 5px; }
    .stat-lbl { font-size: 0.9rem; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 1px; }

    /* 4. Question Review */
    .review-section-title { font-size: 1.5rem; color: white; margin: 40px 0 20px 0; padding-left: 10px; border-left: 4px solid #4361ee; }
    .question-card {
        background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px;
        margin-bottom: 20px; overflow: hidden;
    }
    .q-header { padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; font-weight: 700; color: white; }
    .q-header.correct { background: linear-gradient(90deg, rgba(74, 222, 128, 0.2), rgba(74, 222, 128, 0.05)); border-bottom: 1px solid rgba(74, 222, 128, 0.2); }
    .q-header.incorrect { background: linear-gradient(90deg, rgba(248, 113, 113, 0.2), rgba(248, 113, 113, 0.05)); border-bottom: 1px solid rgba(248, 113, 113, 0.2); }
    .q-body { padding: 20px; color: rgba(255,255,255,0.9); }
    .q-text { font-size: 1.1rem; margin-bottom: 15px; font-weight: 600; }

    /* Answers */
    .answer-list { list-style: none; padding: 0; margin: 0; }
    .answer-item { padding: 10px 15px; margin-bottom: 8px; border-radius: 8px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); display: flex; align-items: center; gap: 10px; }
    .answer-item.user-correct { background: rgba(74, 222, 128, 0.2); border-color: #4ade80; color: #4ade80; }
    .answer-item.user-wrong { background: rgba(248, 113, 113, 0.2); border-color: #f87171; color: #f87171; }
    .answer-item.correct-key { background: rgba(74, 222, 128, 0.1); border: 1px dashed #4ade80; color: #4ade80; }

    /* Buttons */
    .btn-neon-back { background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }
    .btn-neon-back:hover { transform: translateY(-3px); color: white; box-shadow: 0 0 30px rgba(67, 97, 238, 0.6); }
</style>

<div class="result-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <div class="result-hero-card glow-<?= $statusClass ?> animate__animated animate__zoomIn">
                    <div class="score-circle <?= $statusClass ?>">
                        <?= $percentage ?>%
                    </div>
                    
                    <h2 class="result-title">
                        <?php if ($passed): ?>
                            <i class="bi bi-trophy text-warning"></i> <?= Yii::t('app', 'CONGRATULATIONS!') ?>
                        <?php else: ?>
                            <i class="bi bi-emoji-frown text-danger"></i> <?= Yii::t('app', 'TEST COMPLETED') ?>
                        <?php endif; ?>
                    </h2>
                    
                    <p class="result-message">
                        <?php if ($passed): ?>
                            <?= Yii::t('app', 'You passed the test! Well done!') ?> 🎉
                        <?php else: ?>
                            <?= Yii::t('app', 'You need {score}% to pass. Keep studying!', ['score' => $attempt->test->passing_score]) ?> 📚
                        <?php endif; ?>
                    </p>
                </div>

                <div class="row mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                    <div class="col-4">
                        <div class="stat-glass-box">
                            <div class="stat-val text-info"><?= $recalculatedScore ?></div>
                            <div class="stat-lbl"><?= Yii::t('app', 'Points') ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-glass-box">
                            <div class="stat-val text-warning"><?= $totalPossible ?></div>
                            <div class="stat-lbl"><?= Yii::t('app', 'Total') ?></div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-glass-box">
                            <div class="stat-val text-success"><?= $attempt->getDuration() ?>m</div>
                            <div class="stat-lbl"><?= Yii::t('app', 'Time') ?></div>
                        </div>
                    </div>
                </div>

                <hr style="border-color: rgba(255,255,255,0.1); margin-bottom: 30px;">

                <h3 class="review-section-title animate__animated animate__fadeInLeft">
                    <i class="bi bi-list-check"></i> <?= Yii::t('app', 'Detailed Results') ?>
                </h3>

                <?php foreach ($answers as $index => $answer): ?>
                    <?php 
                    $question = $answer->question; 
                    
                    // 🔥 BAZADAN TO'G'RIDAN-TO'G'RI XULOSANI OLAMIZ! Boshqa tekshirmaymiz.
                    $isReallyCorrect = (bool)$answer->is_correct;
                    
                    $userAnswers = array_map('strval', $answer->answerArray ?? []);
                    $correctAnswers = array_map('strval', $question->correctAnswerArray ?? []);
                    ?>
                    
                    <div class="question-card animate__animated animate__fadeInUp">
                        <div class="q-header <?= $isReallyCorrect ? 'correct' : 'incorrect' ?>">
                            <span>
                                <i class="bi bi-<?= $isReallyCorrect ? 'check-circle-fill' : 'x-circle-fill' ?> me-2"></i>
                                <?= Yii::t('app', 'Question') ?> <?= $index + 1 ?>
                            </span>
                            <span class="badge bg-white bg-opacity-10">
                                <?= $isReallyCorrect ? $question->points : 0 ?> / <?= $question->points ?> pts
                            </span>
                        </div>

                        <div class="q-body">
                            <div class="q-text"><?= Html::encode($question->question_text) ?></div>

                            <ul class="answer-list">
                                <?php if ($question->question_type !== 'text' && $question->question_type !== \common\models\TestQuestion::TYPE_TEXT): ?>
                                    
                                    <?php 
                                    if(!empty($question->optionsArray)):
                                        foreach ($question->optionsArray as $key => $optionText): 
                                            // Variantlar ichidagi tekshiruv
                                            $isUserSelected = in_array((string)$key, $userAnswers);
                                            $isCorrectOption = in_array((string)$key, $correctAnswers);
                                            
                                            $itemClass = '';
                                            $icon = '<i class="bi bi-circle me-2 opacity-50"></i>';

                                            if ($isUserSelected) {
                                                if ($isCorrectOption) {
                                                    $itemClass = 'user-correct';
                                                    $icon = '<i class="bi bi-check-circle-fill me-2"></i>';
                                                } else {
                                                    $itemClass = 'user-wrong';
                                                    $icon = '<i class="bi bi-x-circle-fill me-2"></i>';
                                                }
                                            } elseif ($isCorrectOption) {
                                                $itemClass = 'correct-key';
                                                $icon = '<i class="bi bi-check me-2"></i>';
                                            }
                                    ?>
                                            <li class="answer-item <?= $itemClass ?>">
                                                <?= $icon ?> <?= Html::encode($optionText) ?>
                                                <?php if($isCorrectOption && !$isUserSelected): ?>
                                                    <small class="ms-auto opacity-75">(<?= Yii::t('app', 'Correct Answer') ?>)</small>
                                                <?php endif; ?>
                                            </li>
                                    <?php endforeach; endif; ?>

                                <?php else: ?>
                                    <li class="answer-item <?= $isReallyCorrect ? 'user-correct' : 'user-wrong' ?>">
                                        <strong><?= Yii::t('app', 'Your Answer:') ?></strong> 
                                        <?= Html::encode($userAnswers[0] ?? '-') ?>
                                    </li>
                                    <?php if (!$isReallyCorrect): ?>
                                        <li class="answer-item correct-key">
                                            <strong><?= Yii::t('app', 'Expected:') ?></strong> 
                                            <?= Html::encode($correctAnswers[0] ?? '') ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="text-center mt-5 mb-5">
                    <?= Html::a('<i class="bi bi-arrow-left"></i> ' . Yii::t('app', 'Back to Tests'), ['index'], ['class' => 'btn-neon-back']) ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if ($passed): ?>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var duration = 3 * 1000;
    var animationEnd = Date.now() + duration;
    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

    function randomInOut(min, max) { return Math.random() * (max - min) + min; }

    var interval = setInterval(function() {
        var timeLeft = animationEnd - Date.now();
        if (timeLeft <= 0) return clearInterval(interval);
        var particleCount = 50 * (timeLeft / duration);
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInOut(0.1, 0.3), y: Math.random() - 0.2 } }));
        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInOut(0.7, 0.9), y: Math.random() - 0.2 } }));
    }, 250);
});
</script>
<?php endif; ?>