<?php
use yii\helpers\Html;
use common\models\Test;

$this->title = $model->title;
?>

<style>
    /* 🎨 Global Styles */
    body {
        background: #f8f9ff;
    }

    .test-view-page {
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

    .page-header-card h3 {
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
        margin-bottom: 2rem;
    }

    .card-title-section {
        padding: 2rem;
        border-bottom: 3px solid #f0f0ff;
    }

    .card-title-section h2 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .description-text {
        color: #666;
        font-size: 1.1rem;
        line-height: 1.6;
    }

    /* 🏷️ Status Badge */
    .status-badge {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f) !important;
        box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
    }

    /* 📊 Info Cards */
    .info-section {
        padding: 2rem;
    }

    .info-card {
        background: linear-gradient(135deg, #f8f9ff, #ffffff);
        border-radius: 16px;
        padding: 1.5rem;
        border: 2px solid #e8e9ff;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.15);
        border-color: #414fde;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0ff;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #414fde;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        color: #333;
        font-weight: 500;
    }

    /* 📅 Schedule Alert */
    .schedule-alert {
        background: linear-gradient(135deg, rgba(65, 79, 222, 0.1), rgba(107, 116, 255, 0.1));
        border: 2px solid #414fde;
        border-radius: 16px;
        padding: 1.5rem;
        margin: 1.5rem 2rem;
    }

    .schedule-alert h6 {
        color: #414fde;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .schedule-alert strong {
        color: #414fde;
    }

    /* 🔘 Action Buttons */
    .action-section {
        padding: 2rem;
        background: #f8f9ff;
        border-top: 3px solid #e8e9ff;
    }

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

    .btn-primary {
        background: linear-gradient(135deg, #414fde, #6b74ff) !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.4);
    }

    .btn-warning {
        background: linear-gradient(135deg, #ff9800, #f57c00) !important;
        box-shadow: 0 8px 20px rgba(255, 152, 0, 0.3);
        color: white;
    }

    .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(255, 152, 0, 0.4);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #4caf50, #45a049) !important;
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(76, 175, 80, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f) !important;
        box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
    }

    .btn-danger:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(244, 67, 54, 0.4);
    }

    /* 📱 Responsive */
    @media (max-width: 768px) {
        .page-header-card {
            padding: 1.5rem;
        }

        .card-title-section,
        .info-section,
        .action-section {
            padding: 1.5rem;
        }

        .btn-group {
            display: flex !important;
            flex-direction: column !important;
            gap: 0.5rem;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* ✨ Badge Variations */
    .mini-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .mini-badge.bg-warning {
        background: linear-gradient(135deg, #ffc107, #ff9800) !important;
        color: #fff;
    }

    .mini-badge.bg-secondary {
        background: linear-gradient(135deg, #6c757d, #5a6268) !important;
        color: #fff;
    }

    /* 🎯 Stats Grid */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .stat-item {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        border: 2px solid #e8e9ff;
        text-align: center;
        transition: all 0.3s ease;
    }

    .stat-item:hover {
        border-color: #414fde;
        transform: translateY(-3px);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #414fde;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #666;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }
</style>

<div class="test-view-page container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Page Header -->
            <div class="page-header-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><i class="bi bi-clipboard-check"></i> Test Details</h3>
                        <small class="opacity-75">Complete information about this test</small>
                    </div>
                    <?= Html::a('<i class="bi bi-arrow-left"></i> Back to Tests', ['index'], ['class' => 'btn btn-light']) ?>
                </div>
            </div>

            <!-- Main Card -->
            <div class="main-card">
                <!-- Title Section -->
                <div class="card-title-section">
                    <div class="row align-items-start">
                        <div class="col-md-8">
                            <h2><?= Html::encode($model->title) ?></h2>
                            <?php if ($model->description): ?>
                                <p class="description-text"><?= Html::encode($model->description) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="status-badge badge bg-<?= Test::getStatusBadgeClass($model->status) ?>">
                                <?= strtoupper($model->status) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="info-section">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-card">
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-book"></i> Course
                                    </span>
                                    <span class="info-value"><?= Html::encode($model->course->name) ?></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-people"></i> Group
                                    </span>
                                    <span class="info-value">
                                        <?= $model->group ? Html::encode($model->group->name) : '<em>All Students</em>' ?>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-person"></i> Teacher
                                    </span>
                                    <span class="info-value"><?= Html::encode($model->teacher->full_name) ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-card">
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-clock"></i> Duration
                                    </span>
                                    <span class="info-value"><?= $model->duration ?> minutes</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-trophy"></i> Passing Score
                                    </span>
                                    <span class="info-value"><?= $model->passing_score ?>%</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-label">
                                        <i class="bi bi-camera"></i> Face Control
                                    </span>
                                    <span class="info-value">
                                        <?= $model->require_face_control 
                                            ? '<span class="mini-badge bg-warning">Required</span>' 
                                            : '<span class="mini-badge bg-secondary">Not Required</span>' 
                                        ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="stats-row">
                        <div class="stat-item">
                            <div class="stat-number"><?= $model->total_questions ?></div>
                            <div class="stat-label">Questions</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $model->getTotalPoints() ?></div>
                            <div class="stat-label">Total Points</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number"><?= $model->duration ?></div>
                            <div class="stat-label">Minutes</div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Alert -->
                <?php if ($model->start_date || $model->end_date): ?>
                    <div class="schedule-alert">
                        <h6><i class="bi bi-calendar-event"></i> Test Schedule</h6>
                        <div class="row">
                            <?php if ($model->start_date): ?>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-play-circle"></i> Start:</strong> 
                                    <?= Yii::$app->formatter->asDatetime($model->start_date) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($model->end_date): ?>
                                <div class="col-md-6">
                                    <strong><i class="bi bi-stop-circle"></i> End:</strong> 
                                    <?= Yii::$app->formatter->asDatetime($model->end_date) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-section">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="btn-group w-100" role="group">
                                <?= Html::a('<i class="bi bi-pencil"></i> Edit Test', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
                                <?= Html::a('<i class="bi bi-list-check"></i> Manage Questions', ['manage-questions', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="btn-group w-100" role="group">
                                <?= Html::a('<i class="bi bi-bar-chart"></i> View Results', ['results', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                                <?= Html::a('<i class="bi bi-trash"></i> Delete', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this test? This action cannot be undone.',
                                        'method' => 'post',
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>