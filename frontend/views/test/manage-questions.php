<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\TestQuestion;


$this->title = 'Manage Questions: ' . $test->title;
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .manage-questions-page {
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
        padding: 2rem;
    }

    /* 📊 Stats */
    .stats-section {
        background: #f8f9ff;
        padding: 1.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
    }

    /* 🏷️ Badges */
    .badge {
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 600;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        color: white !important;
    }

    /* 📝 Question Cards */
    .question-card {
        background: white;
        border: 2px solid #efefff;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .question-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(65, 79, 222, 0.15);
        border-color: #414fde;
    }

    .question-number {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
    }

    .question-text {
        font-weight: 600;
        color: #333;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    /* 📋 Options */
    .options-list {
        background: #f8f9ff;
        border-radius: 12px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .option-item {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
    }

    .option-item.correct {
        background: rgba(76, 175, 80, 0.1);
        border-left: 4px solid #4caf50;
    }

    .option-letter {
        font-weight: 700;
        color: #414fde;
        margin-right: 0.75rem;
    }

    .option-item.correct .option-letter {
        color: #4caf50;
    }

    /* 🔘 Buttons */
    .btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-3px);
    }

    .btn-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f) !important;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
    }

    .btn-light {
        background: white !important;
        color: #414fde;
    }

    .btn-light:hover {
        transform: scale(1.05);
    }

    /* 📌 Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8f9ff;
        border-radius: 16px;
    }

    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1.5rem;
    }

    .empty-state h5 {
        color: #414fde;
        font-weight: 700;
    }

    /* 🔔 Alert */
    .alert-success {
        background: linear-gradient(135deg, #4caf50, #45a049);
        color: white;
        border-radius: 16px;
        border: none;
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
</style>

<div class="manage-questions-page container-fluid py-4">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="page-card">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4><i class="bi bi-list-check"></i> Manage Questions</h4>
                            <small><?= Html::encode($test->title) ?></small>
                        </div>
                        <?= Html::a('<i class="bi bi-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-light btn-sm']) ?>
                    </div>
                </div>

                <div class="card-body-custom">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle"></i> <?= Yii::$app->session->getFlash('success') ?>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="stats-section d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3">
                            <span class="badge bg-info">
                                <i class="bi bi-question-circle"></i> Questions: <?= count($questions) ?>
                            </span>
                            <span class="badge bg-success">
                                <i class="bi bi-trophy"></i> Points: <?= $test->getTotalPoints() ?>
                            </span>
                        </div>
                        <?= Html::a('<i class="bi bi-plus-circle"></i> Add Question', ['add-question', 'test_id' => $test->id], ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php if (empty($questions)): ?>
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h5>No questions yet</h5>
                            <p class="text-muted">Add your first question to get started</p>
                            <?= Html::a('<i class="bi bi-plus-circle"></i> Add Question', ['add-question', 'test_id' => $test->id], ['class' => 'btn btn-success mt-3']) ?>
                        </div>
                    <?php else: ?>
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="question-card">
                                <div class="d-flex gap-3">
                                    <div class="question-number"><?= $index + 1 ?></div>
                                    <div class="flex-grow-1">
                                        <div class="question-text">
                                            <?= Html::encode($question->question_text) ?>
                                        </div>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge bg-<?= $question->question_type === TestQuestion::TYPE_SINGLE_CHOICE ? 'primary' : ($question->question_type === TestQuestion::TYPE_MULTIPLE_CHOICE ? 'warning' : 'info') ?>">
                                                <?= TestQuestion::getTypeOptions()[$question->question_type] ?>
                                            </span>
                                            <span class="badge bg-success">
                                                <i class="bi bi-star"></i> <?= $question->points ?> pt
                                            </span>
                                        </div>

                                        <?php if ($question->question_type !== TestQuestion::TYPE_TEXT): ?>
                                            <div class="options-list">
                                                <?php foreach ($question->optionsArray as $optIndex => $option): ?>
                                                    <div class="option-item <?= in_array($optIndex, $question->correctAnswerArray) ? 'correct' : '' ?>">
                                                        <span class="option-letter"><?= chr(65 + $optIndex) ?>.</span>
                                                        <span><?= Html::encode($option) ?></span>
                                                        <?php if (in_array($optIndex, $question->correctAnswerArray)): ?>
                                                            <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="options-list">
                                                <div class="option-item correct">
                                                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                    <strong>Expected:</strong> <span class="ms-2"><?= Html::encode($question->correctAnswerArray[0]) ?></span>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <?= Html::a('<i class="bi bi-pencil"></i>', ['edit-question', 'id' => $question->id], ['class' => 'btn btn-warning btn-sm']) ?>
                                        <?= Html::a('<i class="bi bi-trash"></i>', ['delete-question', 'id' => $question->id], [
                                            'class' => 'btn btn-danger btn-sm',
                                            'data-method' => 'post',
                                            'data-confirm' => 'Delete this question?'
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>