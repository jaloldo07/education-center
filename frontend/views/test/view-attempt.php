<?php
use yii\helpers\Html;
use common\models\TestQuestion;

$this->title = 'View Attempt Details';
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .attempt-view-page {
        padding: 2rem 0;
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 🔥 Page Header */
    .page-header-card {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.3);
    }

    .page-header-card h4 {
        font-weight: 700;
        margin: 0;
    }

    .page-header-card .btn-light {
        background: white;
        color: #414fde;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }

    .page-header-card .btn-light:hover {
        transform: translateX(-5px);
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
    }

    /* 📋 Main Card */
    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(65, 79, 222, 0.1);
        overflow: hidden;
    }

    .card-content {
        padding: 2rem;
    }

    /* 👤 Student Info Section */
    .student-section {
        background: linear-gradient(135deg, #f8f9ff, #ffffff);
        padding: 2rem;
        border-radius: 16px;
        border: 2px solid #e8e9ff;
        margin-bottom: 2rem;
    }

    .student-section h5 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    /* 📸 Face Photo */
    .face-photo-container {
        text-align: center;
        margin-right: 2rem;
    }

    .face-photo-container img {
        border-radius: 16px;
        border: 3px solid #414fde;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.2);
        transition: all 0.3s ease;
    }

    .face-photo-container img:hover {
        transform: scale(1.05);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.3);
    }

    .face-photo-container small {
        display: block;
        margin-top: 0.5rem;
        color: #666;
        font-weight: 600;
    }

    /* 📊 Info Table */
    .info-table {
        background: white;
        border-radius: 12px;
        padding: 1rem;
    }

    .info-table tr {
        border-bottom: 1px solid #f0f0ff;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        color: #414fde;
        font-weight: 600;
        padding: 0.75rem;
        width: 40%;
    }

    .info-table td {
        color: #333;
        padding: 0.75rem;
    }

    /* 🏆 Score Card */
    .score-card {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .score-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.3);
    }

    .score-card.passed {
        background: linear-gradient(135deg, #4caf50, #45a049);
    }

    .score-card.failed {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    .score-card .card-body {
        padding: 2rem;
        text-align: center;
        color: white;
    }

    .score-display {
        font-size: 4rem;
        font-weight: 700;
        margin: 1rem 0;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .score-label {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin: 1rem 0;
    }

    .points-info {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-top: 1rem;
    }

    /* ✅ Question Cards */
    .question-card {
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        border: 3px solid;
    }

    .question-card.correct {
        border-color: #4caf50;
    }

    .question-card.incorrect {
        border-color: #f44336;
    }

    .question-card:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .question-header {
        padding: 1.25rem 1.5rem;
        color: white;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .question-header.correct {
        background: linear-gradient(135deg, #4caf50, #45a049);
    }

    .question-header.incorrect {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    .question-header .badge {
        background: white;
        color: #333;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
    }

    .question-body {
        padding: 1.5rem;
        background: white;
    }

    .question-text {
        font-weight: 600;
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0ff;
    }

    /* 📝 Answer Boxes */
    .answer-box {
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }

    .answer-box.correct {
        background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(69, 160, 73, 0.1));
        border: 2px solid #4caf50;
    }

    .answer-box.incorrect {
        background: linear-gradient(135deg, rgba(244, 67, 54, 0.1), rgba(211, 47, 47, 0.1));
        border: 2px solid #f44336;
    }

    .answer-box strong {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 700;
    }

    .answer-box.correct strong {
        color: #4caf50;
    }

    .answer-box.incorrect strong {
        color: #f44336;
    }

    .answer-box ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .answer-box li {
        margin: 0.5rem 0;
        font-weight: 500;
    }

    /* 📋 All Options List */
    .options-list {
        background: #f8f9ff;
        padding: 1rem;
        border-radius: 12px;
        margin-top: 1rem;
    }

    .options-list small {
        color: #666;
        font-weight: 600;
        display: block;
        margin-bottom: 0.75rem;
    }

    .options-list ul {
        margin: 0;
        padding-left: 1.5rem;
    }

    .options-list li {
        margin: 0.5rem 0;
        padding: 0.5rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .options-list li:hover {
        background: white;
    }

    .options-list li.text-success {
        background: rgba(76, 175, 80, 0.1);
        font-weight: 600;
    }

    /* 📊 Section Headers */
    .section-header {
        color: #414fde;
        font-weight: 700;
        margin: 2rem 0 1.5rem 0;
        padding-bottom: 0.75rem;
        border-bottom: 3px solid #e8e9ff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .page-header-card {
            padding: 1.5rem;
        }

        .card-content {
            padding: 1.5rem;
        }

        .student-section {
            padding: 1.5rem;
        }

        .face-photo-container {
            margin-right: 0;
            margin-bottom: 1rem;
        }

        .score-display {
            font-size: 3rem;
        }

        .question-card {
            margin-bottom: 1rem;
        }
    }

    /* ✨ Animations */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .question-card {
        animation: slideIn 0.3s ease forwards;
    }

    .question-card:nth-child(1) { animation-delay: 0.1s; }
    .question-card:nth-child(2) { animation-delay: 0.2s; }
    .question-card:nth-child(3) { animation-delay: 0.3s; }
    .question-card:nth-child(4) { animation-delay: 0.4s; }
    .question-card:nth-child(5) { animation-delay: 0.5s; }
</style>

<div class="attempt-view-page container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="page-header-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4><i class="bi bi-file-earmark-text"></i> Attempt Details</h4>
                        <small class="opacity-75">Student: <?= Html::encode($attempt->student->full_name) ?></small>
                    </div>
                    <?= Html::a('<i class="bi bi-arrow-left"></i> Back to Results', ['results', 'id' => $attempt->test_id], ['class' => 'btn btn-light btn-sm']) ?>
                </div>
            </div>

            <!-- Main Card -->
            <div class="main-card">
                <div class="card-content">
                    <!-- Summary Section -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="student-section">
                                <h5><i class="bi bi-clipboard-check"></i> <?= Html::encode($attempt->test->title) ?></h5>
                                <div class="d-flex align-items-start">
                                    <?php if ($attempt->face_photo): ?>
                                        <div class="face-photo-container">
                                            <img src="<?= Yii::getAlias('@web/uploads/faces/' . $attempt->face_photo) ?>" 
                                                 width="120" height="120"
                                                 alt="Face Control Photo">
                                            <small><i class="bi bi-camera"></i> Face Control</small>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <table class="info-table table table-sm table-borderless mb-0">
                                            <tr>
                                                <th><i class="bi bi-play-circle"></i> Started:</th>
                                                <td><?= Yii::$app->formatter->asDatetime($attempt->started_at) ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-stop-circle"></i> Finished:</th>
                                                <td><?= Yii::$app->formatter->asDatetime($attempt->finished_at) ?></td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-clock"></i> Duration:</th>
                                                <td><?= $attempt->getDuration() ?> minutes</td>
                                            </tr>
                                            <tr>
                                                <th><i class="bi bi-router"></i> IP Address:</th>
                                                <td><code><?= Html::encode($attempt->ip_address) ?></code></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="score-card <?= $attempt->isPassed() ? 'passed' : 'failed' ?>">
                                <div class="card-body">
                                    <div class="score-display"><?= $attempt->score ?>%</div>
                                    <div class="score-label">
                                        <i class="bi bi-<?= $attempt->isPassed() ? 'trophy-fill' : 'x-octagon-fill' ?>"></i>
                                        <?= $attempt->isPassed() ? 'PASSED' : 'FAILED' ?>
                                    </div>
                                    <div class="points-info">
                                        <i class="bi bi-star-fill"></i>
                                        <?= $attempt->points_earned ?> / <?= $attempt->total_points ?> points
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Answers Section -->
                    <h5 class="section-header">
                        <i class="bi bi-list-check"></i> Answer Details
                    </h5>

                    <?php foreach ($answers as $index => $answer): ?>
                        <?php $question = $answer->question; ?>
                        <div class="question-card <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                            <div class="question-header <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                                <span>
                                    <i class="bi bi-<?= $answer->is_correct ? 'check-circle-fill' : 'x-circle-fill' ?>"></i>
                                    Question <?= $index + 1 ?>
                                </span>
                                <span class="badge">
                                    <?= $answer->points_awarded ?> / <?= $question->points ?> points
                                </span>
                            </div>
                            <div class="question-body">
                                <div class="question-text">
                                    <?= Html::encode($question->question_text) ?>
                                </div>

                                <?php if ($question->question_type === TestQuestion::TYPE_SINGLE_CHOICE || $question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE): ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="answer-box <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                                                <strong><i class="bi bi-person"></i> Student's Answer:</strong>
                                                <ul class="mb-0">
                                                    <?php if (!empty($answer->answerArray)): ?>
                                                        <?php foreach ($answer->answerArray as $ans): ?>
                                                            <li><?= Html::encode($question->optionsArray[$ans] ?? 'Unknown') ?></li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li class="text-muted">No answer provided</li>
                                                    <?php endif; ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="answer-box correct">
                                                <strong><i class="bi bi-check-circle"></i> Correct Answer:</strong>
                                                <ul class="mb-0">
                                                    <?php foreach ($question->correctAnswerArray as $correct): ?>
                                                        <li><?= Html::encode($question->optionsArray[$correct] ?? 'Unknown') ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="options-list">
                                        <small><i class="bi bi-list-ul"></i> All Options:</small>
                                        <ul>
                                            <?php foreach ($question->optionsArray as $idx => $opt): ?>
                                                <li class="<?= in_array($idx, $question->correctAnswerArray) ? 'text-success fw-bold' : '' ?>">
                                                    <?= Html::encode($opt) ?>
                                                    <?php if (in_array($idx, $question->correctAnswerArray)): ?>
                                                        <i class="bi bi-check-circle-fill text-success"></i>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>

                                <?php else: ?>
                                    <!-- Text Answer -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="answer-box <?= $answer->is_correct ? 'correct' : 'incorrect' ?>">
                                                <strong><i class="bi bi-person"></i> Student's Answer:</strong>
                                                <div class="mt-2"><?= Html::encode($answer->answerArray[0] ?? 'No answer') ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="answer-box correct">
                                                <strong><i class="bi bi-check-circle"></i> Expected Answer:</strong>
                                                <div class="mt-2"><?= Html::encode($question->correctAnswerArray[0] ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>