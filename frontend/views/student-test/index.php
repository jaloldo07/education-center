<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Available Tests';
?>

<style>
    /* 🎨 Global */
    body {
        background: #f8f9ff;
    }

    .student-test-page {
        animation: fadeSlide 0.6s ease;
    }

    /* 🔥 Header */
    .page-header {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.2);
    }

    .page-header h2 {
        font-weight: 700;
        margin: 0;
    }

    /* 📝 Test Cards */
    .test-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.08);
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        overflow: hidden;
        border: 2px solid transparent;
    }

    .test-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.2);
        border-color: #4caf50;
    }

    .test-card-header {
        background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);
        color: white;
        padding: 1.5rem;
    }

    .test-card-header h4 {
        margin: 0;
        font-weight: 700;
    }

    .test-card-body {
        padding: 1.5rem;
    }

    .test-info-row {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        border-radius: 10px;
        background: #f8f9ff;
    }

    .test-info-row i {
        color: #4caf50;
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .test-card-footer {
        background: #f8f9ff;
        padding: 1rem 1.5rem;
        border-top: 2px solid #efefff;
    }

    /* 🏆 Attempts Sidebar */
    .attempts-card {
        background: white;
        border-radius: 18px;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.08);
        padding: 1.5rem;
        position: sticky;
        top: 20px;
    }

    .attempts-card h5 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .attempt-item {
        background: #f8f9ff;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        border-left: 4px solid #414fde;
        transition: all 0.3s ease;
    }

    .attempt-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(65, 79, 222, 0.1);
    }

    .attempt-score {
        font-size: 1.5rem;
        font-weight: 700;
        color: #4caf50;
    }

    .attempt-score.failed {
        color: #f44336;
    }

    /* 🏷️ Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        color: white !important;
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
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
        background: linear-gradient(135deg, #45a049, #388e3c) !important;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
    }

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
    }

    .btn-lg {
        padding: 14px 28px;
        font-size: 1.1rem;
    }

    /* 📌 Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.1);
    }

    .empty-state i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 1.5rem;
    }

    .empty-state h4 {
        color: #414fde;
        font-weight: 700;
    }

    /* ⚠️ Alert */
    .alert-warning {
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.15), rgba(245, 124, 0, 0.15));
        border: 2px solid #ff9800;
        border-radius: 16px;
        color: #e65100;
        font-weight: 600;
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
        .attempts-card {
            position: relative;
            margin-bottom: 2rem;
        }
    }
</style>

<div class="student-test-page container-fluid py-4">
    <div class="page-header">
        <h2><i class="bi bi-clipboard-check"></i> Available Tests</h2>
        <p class="mb-0">Choose a test to start or view your previous attempts</p>
    </div>

    <div class="row">
        <!-- Tests List -->
        <div class="col-lg-8">
            <?php if (empty($tests)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No tests available</h4>
                    <p class="text-muted">There are no active tests at the moment</p>
                </div>
            <?php else: ?>
                <?php foreach ($tests as $test): ?>
                    <div class="test-card">
                        <div class="test-card-header">
                            <h4><?= Html::encode($test->title) ?></h4>
                            <small><i class="bi bi-book"></i> <?= Html::encode($test->course->name ?? 'N/A') ?></small>
                        </div>

                        <div class="test-card-body">
                            <?php if ($test->description): ?>
                                <p class="text-muted mb-3"><?= Html::encode($test->description) ?></p>
                            <?php endif; ?>

                            <div class="test-info-row">
                                <i class="bi bi-question-circle"></i>
                                <span><strong>Questions:</strong> <?= $test->total_questions ?></span>
                            </div>

                            <div class="test-info-row">
                                <i class="bi bi-clock"></i>
                                <span><strong>Duration:</strong> <?= $test->duration ?> minutes</span>
                            </div>

                            <div class="test-info-row">
                                <i class="bi bi-trophy"></i>
                                <span><strong>Passing Score:</strong> <?= $test->passing_score ?>%</span>
                            </div>

                            <?php if ($test->require_face_control): ?>
                                <div class="alert alert-warning mb-0 mt-3">
                                    <i class="bi bi-camera"></i> <strong>Face Control Required:</strong> You'll need to take a photo before starting
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="test-card-footer">
                            <?= Html::a('<i class="bi bi-play-circle"></i> Start Test', ['start', 'id' => $test->id], ['class' => 'btn btn-success btn-lg']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- My Attempts Sidebar -->
        <div class="col-lg-4">
            <div class="attempts-card">
                <h5><i class="bi bi-clock-history"></i> My Recent Attempts</h5>

                <?php if (empty($attempts)): ?>
                    <p class="text-muted text-center py-3">No attempts yet</p>
                <?php else: ?>
                    <?php foreach ($attempts as $attempt): ?>
                        <div class="attempt-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <small class="text-muted"><?= Html::encode($attempt->test->title) ?></small>
                                <span class="attempt-score <?= $attempt->isPassed() ? '' : 'failed' ?>">
                                    <?= $attempt->score ?>%
                                </span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> <?= Yii::$app->formatter->asRelativeTime($attempt->finished_at) ?>
                                </small>
                                <!-- ✅ FIXED: Changed 'results' to 'result' -->
                                <?= Html::a('View', ['result', 'id' => $attempt->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>