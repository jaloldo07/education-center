<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Schedule;

$this->title = 'Manage Schedule - ' . $group->name;
?>



<style>
    /* ----------- PAGE TITLE ----------- */
    .schedule-page h1 {
        font-size: 2.2rem;
        font-weight: 700;
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        display: flex;
        align-items: center;
    }

    .schedule-page h1 i {
        font-size: 1.9rem;
        margin-right: 10px;
        background: inherit;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* ----------- BUTTON GROUP ----------- */
    .schedule-page .btn {
        border-radius: 8px !important;
        font-weight: 500;
        padding: 7px 14px;
    }

    .schedule-page .btn i {
        margin-right: 4px;
    }

    /* ----------- EMPTY ALERT ----------- */
    .schedule-page .alert {
        border-radius: 15px;
        background: #eef6ff;
        border: 1px solid #d6eaff;
    }

    .schedule-page .alert i {
        color: #0d6efd;
    }

    .schedule-page .alert h4 {
        font-weight: 700;
    }

    /* ----------- CARD STYLE ----------- */
    .schedule-page .card {
        border-radius: 16px;
        overflow: hidden;
    }

    .schedule-page .card-header {
        font-size: 1.2rem;
        font-weight: 600;
    }

    /* ----------- TABLE STYLE ----------- */
    .schedule-page table {
        border-radius: 10px;
        overflow: hidden;
    }

    .schedule-page thead.table-light th {
        background: #e8f1ff !important;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .schedule-page tbody tr {
        transition: 0.2s ease;
    }

    .schedule-page tbody tr:hover {
        background: #f5f9ff;
        transform: scale(1.01);
    }

    /* Table icons */
    .schedule-page td i {
        margin-right: 4px;
    }

    /* BADGES */
    .schedule-page .badge {
        padding: 6px 10px;
        font-size: 0.85rem;
        border-radius: 6px;
    }

    /* ----------- ACTIONS COLUMN ----------- */
    .schedule-page .btn-danger {
        border-radius: 6px !important;
        padding: 4px 8px;
        box-shadow: 0 3px 8px rgba(255, 0, 0, 0.15);
    }

    /* ----------- SUMMARY CARDS ----------- */
    .schedule-page .row .card {
        border-radius: 18px;
        text-align: center;
        padding: 20px 0;
    }

    .schedule-page .row .card h3 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .schedule-page .row .card p {
        font-size: 1rem;
        margin: 0;
        font-weight: 500;
    }

    /* Responsive */
    @media(max-width: 768px) {
        .schedule-page h1 {
            font-size: 1.8rem;
        }

        .schedule-page .btn {
            margin-bottom: 5px;
        }
    }
</style>




<div class="schedule-page">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-calendar-alt"></i> Class Schedule</h1>
            <p class="mb-0 text-light">
                Group: <strong><?= Html::encode($group->name) ?></strong> |
                Course: <strong><?= Html::encode($group->course->name) ?></strong>
            </p>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-calendar"></i> Full Calendar', ['calendar'], ['class' => 'btn btn-info']) ?>
            <?= Html::a('<i class="fas fa-plus"></i> Add Schedule', ['create-schedule', 'id' => $group->id], ['class' => 'btn btn-success']) ?>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['group', 'id' => $group->id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php if (empty($schedules)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-calendar-plus fa-3x mb-3"></i>
            <h4>No schedule created yet</h4>
            <p class="text-muted">Create a class schedule to organize your teaching time</p>
            <?= Html::a('<i class="fas fa-plus"></i> Create First Schedule', ['create-schedule', 'id' => $group->id], ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
    <?php else: ?>
        <!-- Weekly Schedule View -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-week"></i> Weekly Schedule</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Duration</th>
                                <th>Room</th>
                                <th>Status</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <i class="fas fa-calendar-day text-primary"></i>
                                            <?= $schedule->getDayName() ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock text-success"></i>
                                        <?= date('H:i', strtotime($schedule->start_time)) ?> -
                                        <?= date('H:i', strtotime($schedule->end_time)) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= $schedule->getDuration() ?> hours
                                        </span>
                                    </td>
                                    <td><?= Html::encode($schedule->room ?: 'Not specified') ?></td>
                                    <td>
                                        <?php // if ($schedule->is_active): 
                                        ?>
                                        <span class="badge bg-success">Active</span>
                                        <?php // else: 
                                        ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                        <?php // endif; 
                                        ?>
                                    </td>
                                    <td>
                                        <?= Html::a(
                                            '<i class="fas fa-trash"></i>',
                                            ['delete-schedule', 'id' => $schedule->id],
                                            [
                                                'class' => 'btn btn-sm btn-danger',
                                                'data-confirm' => 'Are you sure?',
                                                'data-method' => 'post',
                                            ]
                                        ) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="row mt-4">
            <div class="col-md-4">
                <?= Html::a(
                    '<div class="card bg-primary text-white">
                <div class="card-body text-center">
                <h3>' . count($schedules) . '</h3>
                <p class="mb-0">Classes per Week</p>
                </div>
                </div>',
                    ['calendar'],
                    ['style' => 'text-decoration: none;']
                ) ?>
            </div>
            <div class="col-md-4">
                <?= Html::a(
                    '<div class="card bg-success text-white">
                <div class="card-body text-center">
                <h3>' . array_sum(array_map(fn($s) => $s->getDuration(), $schedules))  . '</h3>
                <p class="mb-0">Total Hours per Week</p>
                </div>
                </div>',
                    ['calendar'],
                    ['style' => 'text-decoration: none;']
                ) ?>
            </div>
            <div class="col-md-4">
                <?= Html::a(
                    '<div class="card bg-info text-white">
                <div class="card-body text-center">
                <h3>' . count($group->students) . '</h3>
                <p class="mb-0">Students Enrolled</p>
                </div>
                </div>',
                    ['calendar'],
                    ['style' => 'text-decoration: none;']
                ) ?>
            </div>
        </div>
    <?php endif; ?>
</div>