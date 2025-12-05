<?php

use yii\helpers\Html;

$this->title = 'My Students';
?>

<style>
    .my-students-page h1 {
        color: #5a94dfff !important;
    }

    /* Avatar Letter */
    .my-students-page .card .rounded-circle {
        background: linear-gradient(135deg, #0d6efd, #0a58ca) !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    /* Card Styling */
    .my-students-page .card {
        border: none;
        border-radius: 18px;
        transition: 0.25s;
    }

    .my-students-page .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    /* Card Body */
    .my-students-page .card-body h5 {
        font-weight: 600;
    }

    .my-students-page .card-body .text-muted {
        color: #6c757d !important;
    }

    /* Student Info Icons */
    .my-students-page .fa-phone {
        color: #28a745 !important;
    }

    .my-students-page .fa-calendar {
        color: #17a2b8 !important;
    }

    .my-students-page .fa-users {
        color: #ffc107 !important;
    }

    /* Group badges */
    .my-students-page .badge {
        padding: 6px 10px;
        font-size: 0.85rem;
        border-radius: 10px;
        background: linear-gradient(135deg, #0d6efd, #0a58ca) !important;
    }

    /* Footer */
    .my-students-page .card-footer {
        border-top: 1px solid #e5e5e5;
        border-radius: 0 0 18px 18px;
    }

    /* Buttons */
    .my-students-page .btn-outline-primary,
    .my-students-page .btn-outline-success {
        border-width: 2px;
        transition: 0.2s;
    }

    .my-students-page .btn-outline-primary:hover {
        background: #0d6efd;
        color: #fff;
    }

    .my-students-page .btn-outline-success:hover {
        background: #198754;
        color: #fff;
    }

    /* Empty State */
    .my-students-page .alert-info {
        background: #f0f7ff;
        border: none;
        border-radius: 20px;
    }

    .my-students-page .alert-info i {
        color: #0d6efd;
    }
</style>



<div class="my-students-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-graduate"></i> My Students (<?= $totalStudents ?>)</h1>
        <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Dashboard', ['dashboard'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php if (empty($students)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-user-slash fa-3x mb-3"></i>
            <h4>No students yet</h4>
            <p class="text-muted">Students will appear here when they enroll in your groups</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($students as $data): ?>
                <?php $student = $data['student']; ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                    <?= strtoupper(substr($student->full_name, 0, 1)) ?>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0"><?= Html::encode($student->full_name) ?></h5>
                                    <p class="text-muted mb-0 small"><?= Html::encode($student->email) ?></p>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-2">
                                <strong><i class="fas fa-phone text-success"></i> Phone:</strong>
                                <?= Html::encode($student->phone) ?>
                            </div>

                            <div class="mb-2">
                                <strong><i class="fas fa-calendar text-info"></i> Enrolled:</strong>
                                <?= Yii::$app->formatter->asDate($student->enrolled_date) ?>
                            </div>

                            <hr>

                            <div>
                                <strong><i class="fas fa-users text-warning"></i> Groups:</strong>
                                <div class="mt-2">
                                    <?php foreach ($data['groups'] as $group): ?>
                                        <span class="badge bg-primary me-1 mb-1">
                                            <?= Html::encode($group->name) ?> - <?= Html::encode($group->course->name) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1" onclick="window.location.href='mailto:<?= $student->email ?>'">
                                    <i class="fas fa-envelope"></i> Email
                                </button>
                                <button class="btn btn-sm btn-outline-success flex-grow-1" onclick="window.location.href='tel:<?= $student->phone ?>'">
                                    <i class="fas fa-phone"></i> Call
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>