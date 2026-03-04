<?php

use yii\helpers\Html;
use common\models\Enrollment; // Model konstantalarini ishlatish uchun

/* @var $this yii\web\View */
/* @var $student common\models\Student */
/* @var $stats array */
/* @var $enrollments common\models\Enrollment[] */
/* @var $schedules common\models\Schedule[] */
/* @var $payments common\models\Payment[] */

$this->title = Yii::t('app', 'My Dashboard');
?>

<style>
    /* Asosiy Konteyner */
    .dashboard-container {
        font-family: 'Nunito', sans-serif;
    }

    /* 1. Welcome Banner (Neon Gradient) */
    .welcome-banner {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.2) 0%, rgba(76, 201, 240, 0.1) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 40px;
        color: white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        background: rgba(67, 97, 238, 0.4);
        filter: blur(50px);
        border-radius: 50%;
    }

    /* 2. Glass Stat Cards */
    .stat-card-link {
        text-decoration: none !important;
        display: block;
        height: 100%;
    }

    .stat-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
        color: white;
    }

    .stat-card-link:hover .stat-box {
        transform: translateY(-8px);
        background: rgba(255, 255, 255, 0.1);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
        border-color: var(--accent-color);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        display: inline-block;
        text-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 5px;
        color: white;
    }

    .stat-label {
        color: rgba(255, 255, 255, 0.6);
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* 3. Glass Table Container */
    .content-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .content-header {
        padding: 20px 25px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(255, 255, 255, 0.02);
    }

    .content-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--accent-color);
        margin: 0;
        text-shadow: 0 0 10px rgba(76, 201, 240, 0.3);
    }

    /* Jadval (Dark Glass) */
    .custom-table {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.8);
        width: 100%;
    }

    .custom-table thead th {
        background-color: rgba(0, 0, 0, 0.2);
        color: var(--accent-color);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 15px;
    }

    .custom-table tbody td {
        padding: 15px;
        vertical-align: middle;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-weight: 500;
    }

    .custom-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    /* Notification Box */
    .notification-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 15px;
        margin-top: 20px;
    }

    /* Action Button */
    .btn-action {
        background: transparent;
        color: var(--accent-color) !important;
        border: 1px solid var(--accent-color);
        border-radius: 50px;
        padding: 5px 15px;
        font-size: 0.85rem;
        text-decoration: none !important;
        transition: all 0.3s;
    }

    .btn-action:hover {
        background: var(--accent-color);
        color: #000 !important;
        box-shadow: 0 0 10px var(--accent-color);
    }

    /* Pay Now Button Style */
    .btn-pay {
        color: #ffc107 !important;
        border-color: #ffc107 !important;
    }

    .btn-pay:hover {
        background: #ffc107 !important;
        color: #000 !important;
        box-shadow: 0 0 10px #ffc107;
    }
</style>

<div class="student-dashboard dashboard-container">

    <div class="welcome-banner">
        <h1 class="display-5 font-weight-bold text-white">
            <?= Yii::t('app', 'Welcome, {name}!', ['name' => Html::encode($student->full_name)]) ?>
        </h1>
        <p class="lead mb-0 text-white-50">
            <?= Yii::t('app', 'Here\'s your learning dashboard and progress overview.') ?>
        </p>

        <?php
        $notifications = \common\models\Notification::find()
            ->where(['user_id' => Yii::$app->user->id, 'is_read' => false])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        ?>

        <?php if (!empty($notifications)): ?>
            <div class="notification-box mt-4">
                <h6 class="mb-3 font-weight-bold text-info">
                    <i class="fas fa-bell mr-2"></i> <?= Yii::t('app', 'New Notifications') ?>
                    <span class="badge bg-danger ms-2"><?= count($notifications) ?></span>
                </h6>

                <?php foreach ($notifications as $notification): ?>
                    <div class="d-flex justify-content-between align-items-center rounded p-2 mb-2" style="background: rgba(255,255,255,0.1);">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-<?= $notification->type === 'success' ? 'check-circle text-success' : 'info-circle text-info' ?> mr-2"></i>
                            
                            <span class="small font-weight-bold text-white">
                                <?= Html::encode(Yii::t('app', $notification->title)) ?>
                            </span>
                            <span class="small text-white-50 ms-2">
                                - <?= Html::encode(Yii::t('app', $notification->message)) ?>
                            </span>

                        </div>
                        <?= Html::a('<i class="fas fa-times"></i>', ['mark-notification-read', 'id' => $notification->id], [
                            'class' => 'text-white-50 ms-2',
                            'style' => 'text-decoration: none;',
                            'data-method' => 'post',
                        ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <a href="#enrolled-courses" class="stat-card-link">
                <div class="stat-box">
                    <div class="stat-icon" style="color: #4cc9f0;">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-number"><?= $stats['totalEnrollments'] ?></div>
                    <div class="stat-label"><?= Yii::t('app', 'Total Enrollments') ?></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="#enrolled-courses" class="stat-card-link">
                <div class="stat-box">
                    <div class="stat-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number text-success"><?= $stats['activeEnrollments'] ?></div>
                    <div class="stat-label"><?= Yii::t('app', 'Active Courses') ?></div>
                </div>
            </a>
        </div>

        <div class="col-md-4 mb-3">
            <a href="#payment-history" class="stat-card-link">
                <div class="stat-box">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-number text-warning">
                        <?= number_format($stats['totalPayments'], 0) ?>
                        <small style="font-size: 0.8rem;">UZS</small>
                    </div>
                    <div class="stat-label"><?= Yii::t('app', 'Total Paid') ?></div>
                </div>
            </a>
        </div>
    </div>

    <div class="content-card" id="enrolled-courses">
        <div class="content-header">
            <h3 class="content-title">
                <i class="fas fa-book-reader mr-2"></i> <?= Yii::t('app', 'My Enrolled Courses') ?>
            </h3>
            <?= Html::a(Yii::t('app', 'Browse All'), ['/site/courses'], ['class' => 'btn btn-sm btn-outline-info rounded-pill']) ?>
        </div>

        <div class="card-body p-0">
            <?php if (empty($enrollments)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-white-50 mb-3"></i>
                    <p class="text-white-50"><?= Yii::t('app', 'You are not enrolled in any courses yet.') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Course') ?></th>
                                <th><?= Yii::t('app', 'Group') ?></th>
                                <th><?= Yii::t('app', 'Teacher') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <?php 
                                // Kursni enrollment ichidan ajratib olamiz
                                $course = $enrollment->group->course ?? null; 
                                ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-white"><?= Html::encode($course->name ?? 'N/A') ?></div>
                                        <small class="text-white-50"><?= Yii::$app->formatter->asDate($enrollment->enrolled_on) ?></small>
                                    </td>
                                    <td><?= Html::encode($enrollment->group->name ?? 'N/A') ?></td>
                                    <td><?= Html::encode($enrollment->group->teacher->full_name ?? 'N/A') ?></td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'secondary';
                                            $statusLabel = $enrollment->status;
                                            
                                            if ($enrollment->status === Enrollment::STATUS_ACTIVE) {
                                                $badgeClass = 'success';
                                                $statusLabel = 'Active';
                                            } elseif ($enrollment->status === Enrollment::STATUS_WAITING_PAYMENT) {
                                                $badgeClass = 'warning text-dark';
                                                $statusLabel = 'Payment Required';
                                            }
                                        ?>
                                        <span class="badge bg-<?= $badgeClass ?>">
                                            <?= Yii::t('app', $statusLabel) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <?php if ($enrollment->status === Enrollment::STATUS_ACTIVE): ?>
                                            
                                            <?= Html::a(
                                                '<i class="fas fa-play me-1"></i> ' . Yii::t('app', 'Lessons'),
                                                ['/student-lesson/course', 'course_id' => $course->id],
                                                ['class' => 'btn-action']
                                            ) ?>

                                        <?php elseif ($enrollment->status === Enrollment::STATUS_WAITING_PAYMENT): ?>
                                            
                                            <?= Html::a(
                                                '<i class="fas fa-credit-card me-1"></i> ' . Yii::t('app', 'Pay Now'),
                                                ['/payment/create', 'course_id' => $course->id],
                                                ['class' => 'btn-action btn-pay']
                                            ) ?>

                                        <?php else: ?>
                                            <span class="text-muted small"><?= Yii::t('app', 'Pending') ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-card">
        <div class="content-header">
            <h3 class="content-title">
                <i class="far fa-calendar-alt mr-2"></i> <?= Yii::t('app', 'My Class Schedule') ?>
            </h3>
        </div>
        <div class="card-body p-0">
            <?php if (empty($schedules)): ?>
                <div class="text-center py-5">
                    <p class="text-white-50"><?= Yii::t('app', 'No active class schedules found.') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Day & Time') ?></th>
                                <th><?= Yii::t('app', 'Subject') ?></th>
                                <th><?= Yii::t('app', 'Room') ?></th>
                                <th><?= Yii::t('app', 'Teacher') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold text-info"><?= Yii::t('app', $schedule->getDayName()) ?></span>
                                        <br>
                                        <small class="text-white-50">
                                            <?= date('H:i', strtotime($schedule->start_time)) ?> - <?= date('H:i', strtotime($schedule->end_time)) ?>
                                        </small>
                                    </td>
                                    <td>
                                        <?= Html::encode($schedule->group->course->name ?? 'N/A') ?>
                                        <span class="badge bg-dark text-white-50 ms-1 border border-secondary"><?= Html::encode($schedule->group->name ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <?= Html::encode($schedule->room ?: 'Online') ?>
                                        </span>
                                    </td>
                                    <td><?= Html::encode($schedule->group->teacher->full_name ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-card" id="payment-history">
        <div class="content-header d-flex justify-content-between align-items-center">
            <h3 class="content-title mb-0">
                <i class="fas fa-history mr-2"></i> <?= Yii::t('app', 'Payment History') ?>
            </h3>

            <?= Html::a('<i class="fas fa-plus-circle"></i> ' . Yii::t('app', 'New Payment'), ['/payment/create'], [
                'class' => 'btn btn-sm text-white',
                'style' => 'background: linear-gradient(135deg, #4ade80, #22c55e); border: none; font-weight: 600; box-shadow: 0 0 10px rgba(74, 222, 128, 0.4); border-radius: 8px; padding: 8px 16px;'
            ]) ?>
        </div>
        <div class="card-body p-0">
            <?php if (empty($payments)): ?>
                <div class="text-center py-5">
                    <p class="text-white-50"><?= Yii::t('app', 'No payment history found.') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Date') ?></th>
                                <th><?= Yii::t('app', 'Course') ?></th>
                                <th><?= Yii::t('app', 'Amount') ?></th>
                                <th><?= Yii::t('app', 'Type') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= Yii::$app->formatter->asDate($payment->payment_date) ?></td>
                                    <td><?= Html::encode($payment->course->name ?? 'N/A') ?></td>
                                    <td class="fw-bold text-warning">
                                        <?= number_format($payment->amount, 0) ?> UZS
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?= Yii::t('app', ucfirst($payment->payment_type)) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>