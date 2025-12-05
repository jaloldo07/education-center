<?php

use yii\helpers\Html;

$this->title = 'My Dashboard';
?>


<style>
    /* 🔹 Body soft background */
    body {
        padding-bottom: 40px;
    }


    html {
        scroll-behavior: smooth;
    }

    .stat-card:hover {
        transform: scale(1.05);
        transition: all 0.3s;
    }


    /* 🔥 Welcome (Jumbotron) */
    .student-dashboard .jumbotron {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(65, 79, 222, 0.2);
        animation: fadeSlide 0.6s ease;
    }

    .student-dashboard .jumbotron h1 {
        font-weight: 700;
    }

    /* 🔔 Notifications */
    .student-dashboard .alert {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .student-dashboard .alert .btn-light {
        background: rgba(255, 255, 255, 0.8) !important;
        border-radius: 10px;
        transition: 0.3s ease;
    }

    .student-dashboard .alert .btn-light:hover {
        background: #fff !important;
        transform: scale(1.1);
    }

    /* 📊 Statistics Cards */
    .student-dashboard .card.border-primary,
    .student-dashboard .card.border-success,
    .student-dashboard .card.border-info {
        border-width: 3px !important;
        border-radius: 18px;
        transition: 0.3s ease;
    }

    .student-dashboard .card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 25px rgba(65, 79, 222, 0.15);
    }

    /* 🟦 Statistic headings colorized */
    .student-dashboard .text-primary {
        color: #414fde !important;
    }

    .student-dashboard .text-info {
        color: #4968ff !important;
    }

    .student-dashboard .text-success {
        color: #4caf50 !important;
    }

    /* 📚 Tables */
    .table thead {
        background: #414fde;
        color: white;
    }

    .table tbody tr:hover {
        background: rgba(65, 79, 222, 0.08);
        transition: 0.2s;
    }

    /* 🟩 Badge improvements */
    .badge {
        padding: 6px 10px;
        border-radius: 10px;
        font-size: 0.85rem;
    }

    /* 🧾 Card headers */
    .card-header {
        background: white !important;
        border-bottom: 2px solid #efefff;
        padding: 15px;
        border-radius: 14px 14px 0 0 !important;
    }

    /* 🔄 Card styling */
    .card {
        border-radius: 18px !important;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.08);
        transition: 0.3s ease;
    }

    .card:hover {
        transform: translateY(-4px);
    }

    /* 💳 Payment Type Badge */
    .badge.bg-info {
        background-color: #414fde !important;
    }

    /* ✨ Soft animation */
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

    /* 📌 Buttons */
    .btn-primary {
        background: #414fde !important;
        border-color: #414fde !important;
        border-radius: 10px;
        padding: 10px 18px;
    }

    .btn-primary:hover {
        background: #333dcc !important;
    }

    .btn-outline-primary {
        color: #414fde;
        border-color: #414fde;
    }

    .btn-outline-primary:hover {
        background: #414fde;
        color: white;
    }

    /* 🔘 Smaller text muted fix */
    .text-muted {
        color: #6c6e8a !important;
    }

    /* Smooth shadows for nested items */
    .bg-white.bg-opacity-25 {
        background: rgba(255, 255, 255, 0.25) !important;
        border-radius: 12px;
    }
</style>


<div class="student-dashboard">
    <div class="jumbotron bg-light p-4 rounded mb-4">
        <h1 class="display-4">Welcome, <?= Html::encode($student->full_name) ?>!</h1>

        <?php
        $notifications = \common\models\Notification::find()
            ->where(['user_id' => Yii::$app->user->id, 'is_read' => false])
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(5)
            ->all();
        ?>

        <?php if (!empty($notifications)): ?>
            <!-- Notifications -->
            <div class="alert alert-dismissible fade show shadow mb-4 border-0" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h5 class="alert-heading mb-3">
                    <i class="fas fa-bell"></i> You have <?= count($notifications) ?> new notification(s)
                </h5>
                <?php foreach ($notifications as $notification): ?>
                    <div class="bg-white bg-opacity-25 rounded p-3 mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1"><i class="fas fa-<?= $notification->type === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i> <?= Html::encode($notification->title) ?></h6>
                                <p class="mb-0 small"><?= Html::encode($notification->message) ?></p>
                                <small class="text-white-50"><?= Yii::$app->formatter->asRelativeTime($notification->created_at) ?></small>
                            </div>
                            <?= Html::a('<i class="fas fa-times"></i>', ['mark-notification-read', 'id' => $notification->id], [
                                'class' => 'btn btn-sm btn-light',
                                'data-method' => 'post',
                            ]) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?= Html::a('View All Notifications', ['notifications'], ['class' => 'btn btn-light btn-sm mt-2']) ?>
            </div>
        <?php endif; ?>

        <p class="lead">Here's your learning dashboard and progress overview.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <a href="#enrolled-courses" style="text-decoration: none;">
                <div class="card text-center border-primary" style="cursor: pointer;">
                    <div class="card-body">
                        <h3><i class="fas fa-clipboard-list"></i> <?= $stats['totalEnrollments'] ?></h3>
                        <p class="card-text">Total Enrollments</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#enrolled-courses" style="text-decoration: none;">
                <div class="card text-center border-success" style="cursor: pointer;">
                    <div class="card-body">
                        <h3 class="text-success ><i class=" fas fa-check-circle"></i> <?= $stats['activeEnrollments'] ?></h3>
                        <p class="card-text">Active Courses</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-4">
            <a href="#payment-history" style="text-decoration: none;">
                <div class="card text-center border-info" style="cursor: pointer;">
                    <div class="card-body">
                        <h3 class="text-info"><i class="fas fa-money-bill-wave"></i> <?= number_format($stats['totalPayments'], 0) ?></h3>
                        <p class="mb-0">Total Paid (UZS)</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- My Enrolled Courses -->
    <div class="card mb-4" id="enrolled-courses" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
        <div class="card-header">
            <h3 class="card-title mb-0">My Enrolled Courses</h3>
        </div>
        <div class="card-body">
            <?php if (empty($enrollments)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">You are not enrolled in any courses yet.</p>
                    <?= Html::a('Browse Courses', ['/site/courses'], ['class' => 'btn btn-primary']) ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Group</th>
                                <th>Teacher</th>
                                <th>Enrolled On</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                                <tr>
                                    <td><?= Html::encode($enrollment->group->course->name ?? 'N/A') ?></td>
                                    <td><?= Html::encode($enrollment->group->name ?? 'N/A') ?></td>
                                    <td><?= Html::encode($enrollment->group->teacher->full_name ?? 'N/A') ?></td>
                                    <td><?= Yii::$app->formatter->asDate($enrollment->enrolled_on) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $enrollment->status == 'active' ? 'success' : 'secondary' ?>">
                                            <?= ucfirst($enrollment->status) ?>
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




    <!-- Payment History -->
    <div class="card" id="payment-history" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
        <div class="card-header bg-white">
            <h3 class="card-title mb-0">Payment History</h3>
        </div>
        <div class="card-body">
            <?php if (empty($payments)): ?>
                <div class="text-center py-4">
                    <p class="text-muted">No payment history found.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Course</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?= Yii::$app->formatter->asDate($payment->payment_date) ?></td>
                                    <td><?= Html::encode($payment->course->name ?? 'N/A') ?></td>
                                    <td><?= number_format($payment->amount, 0) ?> UZS</td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst($payment->payment_type) ?>
                                        </span>
                                    </td>
                                    <td><?= Html::encode($payment->note) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>




    <?= Html::a(
        '<i class="bi bi-clipboard-check"></i> Available Tests',
        ['/student-test/index'],
        [
            'class' => 'btn btn-success',
            'style' => 'background: linear-gradient(135deg, #414fde, #6b74ff) !important; 
                    border: none; 
                    border-radius: 12px; 
                    padding: 12px 24px; 
                    font-weight: 600; 
                    box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3); 
                    transition: all 0.3s ease;
                    margin-top: 20px;'
        ]
    ) ?>


</div>