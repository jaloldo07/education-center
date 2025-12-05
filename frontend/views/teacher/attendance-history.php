<?php

use yii\helpers\Html;
use common\models\Attendance;

$this->title = 'Attendance History - ' . $group->name;
?>

<div class="attendance-history-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-history"></i> Attendance History</h1>
            <p class="text-white mb-0">
                Group: <strong><?= Html::encode($group->name) ?></strong> |
                Course: <strong><?= Html::encode($group->course->name) ?></strong>
            </p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['attendance', 'id' => $group->id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php if (empty($attendanceByDate)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-calendar-times fa-3x mb-3"></i>
            <h4>No attendance records yet</h4>
            <p class="text-muted">Start taking attendance to see history here</p>
        </div>
    <?php else: ?>
        <?php foreach ($attendanceByDate as $date => $records): ?>
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-day"></i>
                        <?= Yii::$app->formatter->asDate($date, 'php:l, F d, Y') ?>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $i => $attendance): ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td><?= Html::encode($attendance->student->full_name) ?></td>
                                        <td>
                                            <span class="badge bg-<?= Attendance::getStatusBadgeClass($attendance->status) ?>">
                                                <?= ucfirst($attendance->status) ?>
                                            </span>
                                        </td>
                                        <td><?= Html::encode($attendance->note) ?: '-' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row text-center">
                        <div class="col-3">
                            <strong><?= count(array_filter($records, fn($r) => $r->status === 'present')) ?></strong>
                            <br><small class="text-success">Present</small>
                        </div>
                        <div class="col-3">
                            <strong><?= count(array_filter($records, fn($r) => $r->status === 'absent')) ?></strong>
                            <br><small class="text-danger">Absent</small>
                        </div>
                        <div class="col-3">
                            <strong><?= count(array_filter($records, fn($r) => $r->status === 'late')) ?></strong>
                            <br><small class="text-warning">Late</small>
                        </div>
                        <div class="col-3">
                            <strong><?= count(array_filter($records, fn($r) => $r->status === 'excused')) ?></strong>
                            <br><small class="text-info">Excused</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>


<style>
    .attendance-history-page h1 {
        font-weight: 700;
        font-size: 32px;
        color: #6c2ba0ff;
    }

    .attendance-history-page .btn-secondary {
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
    }

    .attendance-history-page .card {
        border-radius: 15px;
        overflow: hidden;
        transition: 0.25s ease;
    }

    .attendance-history-page .card:hover {
        transform: translateY(-3px);
    }

    /* Card headers */
    .attendance-history-page .card-header {
        padding: 18px 25px;
        font-size: 18px;
        background: linear-gradient(45deg, #007bff, #4da3ff) !important;
    }

    .attendance-history-page .card h5 {
        font-size: 18px;
        font-weight: 600;
    }

    /* Tables */
    .attendance-history-page table {
        font-size: 15px;
    }

    .attendance-history-page thead tr {
        background: #f4f7f9 !important;
    }

    .attendance-history-page thead th {
        font-weight: 600;
        color: #34495e;
    }

    .attendance-history-page tbody tr:hover {
        background: #eef6ff;
        transition: 0.2s;
    }

    .attendance-history-page tbody td {
        padding: 14px 12px;
        vertical-align: middle;
    }

    /* Badges (Present, Absent…) */
    .badge {
        padding: 8px 12px;
        font-size: 13px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Footer statistics */
    .attendance-history-page .card-footer {
        padding: 18px;
        border-top: 1px solid #e9ecef;
    }

    .attendance-history-page .card-footer .col-3 strong {
        font-size: 18px;
        font-weight: 700;
    }

    .attendance-history-page .card-footer small {
        font-size: 13px;
        font-weight: 600;
    }

    /* Empty State Style */
    .attendance-history-page .alert-info {
        border-radius: 12px;
        background: #e8f4ff;
        border: 1px solid #cde7ff;
    }

    .attendance-history-page .alert-info h4 {
        font-weight: 600;
        color: #2c3e50;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .attendance-history-page h1 {
            font-size: 26px;
        }

        .attendance-history-page table {
            font-size: 13.5px;
        }
    }
</style>