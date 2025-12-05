<?php
use yii\helpers\Html;
use common\models\TestQuestion;

$this->title = 'Test Result';
$passed = $attempt->isPassed();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Result Card -->
            <div class="card shadow-lg border-<?= $passed ? 'success' : 'danger' ?>">
                <div class="card-header bg-<?= $passed ? 'success' : 'danger' ?> text-white text-center py-4">
                    <h2 class="mb-0">
                        <i class="bi bi-<?= $passed ? 'check-circle-fill' : 'x-circle-fill' ?>"></i>
                        <?= $passed ? 'CONGRATULATIONS!' : 'TEST COMPLETED' ?>
                    </h2>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h1 class="display-1 fw-bold text-<?= $passed ? 'success' : 'danger' ?>">
                            <?= $attempt->score ?>%
                        </h1>
                        <p class="lead">
                            <?php if ($passed): ?>
                                You passed the test! Well done! 🎉
                            <?php else: ?>
                                You need <?= $attempt->test->passing_score ?>% to pass. Keep studying! 📚
                            <?php endif; ?>
                        </p>
                    </div>

                    <!-- Statistics -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="mb-0"><?= $attempt->points_earned ?></h4>
                                    <small class="text-muted">Points Earned</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="mb-0"><?= $attempt->total_points ?></h4>
                                    <small class="text-muted">Total Points</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h4 class="mb-0"><?= $attempt->getDuration() ?> min</h4>
                                    <small class="text-muted">Duration</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Detailed Results -->
                    <h5 class="mb-3"><i class="bi bi-list-check"></i> Detailed Results</h5>
                    
                    <?php foreach ($answers as $index => $answer): ?>
                        <?php 
                        $question = $answer->question;
                        // DEBUG: Uncomment to see data
                        // echo '<pre>Answer ' . ($index+1) . ': '; var_dump($answer->answerArray); echo '</pre>';
                        ?>
                        <div class="card mb-3 border-<?= $answer->is_correct ? 'success' : 'danger' ?>">
                            <div class="card-header bg-<?= $answer->is_correct ? 'success' : 'danger' ?> text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-<?= $answer->is_correct ? 'check-circle' : 'x-circle' ?>"></i>
                                        Question <?= $index + 1 ?>
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <?= $answer->is_correct ? $question->points : 0 ?> / <?= $question->points ?> points
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="fw-bold"><?= Html::encode($question->question_text) ?></p>

                                <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE || $question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Your Answer:</small>
                                            <ul class="mb-0">
                                                <?php 
                                                $hasAnswer = false;
                                                if (!empty($answer->answerArray) && is_array($answer->answerArray)): 
                                                    foreach ($answer->answerArray as $ans): 
                                                        // Skip empty, null, or invalid values
                                                        if ($ans === '' || $ans === null || !is_numeric($ans) && !is_string($ans)) continue;
                                                        if (!isset($question->optionsArray[$ans])) continue;
                                                        $hasAnswer = true;
                                                ?>
                                                        <li class="<?= $answer->is_correct ? 'text-success' : 'text-danger' ?>">
                                                            <?= Html::encode($question->optionsArray[$ans]) ?>
                                                        </li>
                                                <?php 
                                                    endforeach;
                                                endif;
                                                if (!$hasAnswer): 
                                                ?>
                                                    <li class="text-muted">No answer provided</li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                        <?php if (!$answer->is_correct): ?>
                                            <div class="col-md-6">
                                                <small class="text-muted">Correct Answer:</small>
                                                <ul class="mb-0">
                                                    <?php 
                                                    if (!empty($question->correctAnswerArray) && is_array($question->correctAnswerArray)): 
                                                        foreach ($question->correctAnswerArray as $correct): 
                                                            if ($correct === '' || $correct === null) continue;
                                                            if (!isset($question->optionsArray[$correct])) continue;
                                                    ?>
                                                            <li class="text-success fw-bold">
                                                                <?= Html::encode($question->optionsArray[$correct]) ?>
                                                            </li>
                                                    <?php 
                                                        endforeach;
                                                    endif; 
                                                    ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                <?php else: ?>
                                    <!-- Text Answer -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">Your Answer:</small>
                                            <div class="alert alert-<?= $answer->is_correct ? 'success' : 'danger' ?> py-2">
                                                <?= Html::encode($answer->answerArray[0] ?? 'No answer') ?>
                                            </div>
                                        </div>
                                        <?php if (!$answer->is_correct): ?>
                                            <div class="col-md-6">
                                                <small class="text-muted">Expected Answer:</small>
                                                <div class="alert alert-success py-2">
                                                    <?= Html::encode($question->correctAnswerArray[0]) ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="text-center mt-4">
                        <?= Html::a('<i class="bi bi-arrow-left"></i> Back to Tests', ['index'], ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($passed): ?>
<script>
// Celebration effect
document.addEventListener('DOMContentLoaded', function() {
    // Add confetti or celebration animation here if you want
    console.log('🎉 Congratulations on passing!');
});
</script>
<?php endif; ?>






<style>
    body {
        background: #f8f9ff;
    }

    /* 🔥 Header */
    .page-header {
        background: linear-gradient(135deg, #4caf50, #45a049);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.2);
    }

    /* 📝 Card */
    .main-card {
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

    .card-body-custom {
        padding: 2rem;
    }

    /* 📸 Face Control */
    #video, #canvas {
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    #photo-preview img {
        border-radius: 16px;
        border: 3px solid #4caf50;
    }

    /* ⏰ Timer */
    .timer-display {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
        padding: 1rem 2rem;
        border-radius: 12px;
        font-size: 1.5rem;
        font-weight: 700;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
    }

    /* 📊 Progress */
    .progress {
        height: 12px;
        border-radius: 10px;
        background: #f8f9ff;
    }

    .progress-bar {
        background: linear-gradient(135deg, #4caf50, #45a049);
        border-radius: 10px;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px) scale(1.05);
    }

    /* 🏅 Result Score */
    .score-display {
        font-size: 5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4caf50, #45a049);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ✅ Question Cards */
    .question-result-card {
        border-radius: 16px;
        border: 3px solid;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .question-result-card.correct {
        border-color: #4caf50;
    }

    .question-result-card.incorrect {
        border-color: #f44336;
    }

    .question-result-header {
        padding: 1rem 1.5rem;
        color: white;
        font-weight: 700;
    }

    .question-result-header.correct {
        background: linear-gradient(135deg, #4caf50, #45a049);
    }

    .question-result-header.incorrect {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }
</style>