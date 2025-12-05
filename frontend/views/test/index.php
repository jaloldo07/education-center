<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Test;

$this->title = 'My Tests';
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .test-list-page {
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
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.15);
        border-color: #414fde;
    }

    .test-card-header {
        background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: none;
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
        color: #414fde;
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }

    .test-card-footer {
        background: #f8f9ff;
        padding: 2px;
        border-top: 2px solid #efefff;
    }

    /* 🏷️ Badges */
    .badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        color: white !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #414fde, #333dcc) !important;
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
        background: linear-gradient(135deg, #333dcc, #5563ff) !important;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4);
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
    }

    .btn-info {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-info:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
    }

    .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 152, 0, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f) !important;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(244, 67, 54, 0.4);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.875rem;
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

    /* 🔔 Alert */
    .alert {
        border-radius: 16px;
        border: none;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #4caf50, #45a049);
        color: white;
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
        .page-header {
            padding: 1.5rem;
        }
        .test-card-header,
        .test-card-body {
            padding: 1rem;
        }
    }
</style>

<div class="test-list-page container-fluid py-4">
    <!-- Header -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <h2><i class="bi bi-list-check"></i> My Tests</h2>
        <?= Html::a('<i class="bi bi-plus-circle"></i> Create New Test', ['create'], ['class' => 'btn btn-light']) ?>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle"></i> <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tests Grid -->
    <div class="row">
        <?php if (empty($tests)): ?>
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No tests yet</h4>
                    <p class="text-muted">Create your first test to get started</p>
                    <?= Html::a('<i class="bi bi-plus-circle"></i> Create Test', ['create'], ['class' => 'btn btn-primary mt-3']) ?>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($tests as $test): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="test-card">
                        <!-- Card Header -->
                        <div class="test-card-header">
                            <h4><?= Html::encode($test->title) ?></h4>
                            <span class="badge bg-<?= $test->status === Test::STATUS_ACTIVE ? 'success' : ($test->status === Test::STATUS_DRAFT ? 'warning' : 'secondary') ?>">
                                <?= ucfirst($test->status) ?>
                            </span>
                        </div>

                        <!-- Card Body -->
                        <div class="test-card-body">
                            <div class="test-info-row">
                                <i class="bi bi-book"></i>
                                <span><strong>Course:</strong> <?= Html::encode($test->course->name ?? 'N/A') ?></span>
                            </div>

                            <?php if ($test->group): ?>
                                <div class="test-info-row">
                                    <i class="bi bi-people"></i>
                                    <span><strong>Group:</strong> <?= Html::encode($test->group->name) ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="test-info-row">
                                <i class="bi bi-question-circle"></i>
                                <span><strong>Questions:</strong> <?= $test->total_questions ?> questions</span>
                            </div>

                            <div class="test-info-row">
                                <i class="bi bi-clock"></i>
                                <span><strong>Duration:</strong> <?= $test->duration ?> minutes</span>
                            </div>

                            <div class="test-info-row">
                                <i class="bi bi-trophy"></i>
                                <span><strong>Pass Score:</strong> <?= $test->passing_score ?>%</span>
                            </div>

                            <?php if ($test->require_face_control): ?>
                                <div class="test-info-row">
                                    <i class="bi bi-camera"></i>
                                    <span class="badge bg-primary">Face Control Required</span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Card Footer -->
                        <div class="test-card-footer d-flex gap-2 flex-wrap">
                            <?= Html::a('<i class="bi bi-list-ul"></i> Questions', ['manage-questions', 'id' => $test->id], ['class' => 'btn btn-success btn-sm']) ?>
                            <?= Html::a('<i class="bi bi-eye"></i> View', ['view', 'id' => $test->id], ['class' => 'btn btn-info btn-sm']) ?>
                            <?= Html::a('<i class="bi bi-bar-chart"></i> Results', ['results', 'id' => $test->id], ['class' => 'btn btn-warning btn-sm']) ?>
                            <?= Html::a('<i class="bi bi-pencil"></i>', ['update', 'id' => $test->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            <?= Html::a('<i class="bi bi-trash"></i>', ['delete', 'id' => $test->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to delete this test?'
                            ]) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>