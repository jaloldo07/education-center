<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Admin Dashboard');
?>

<div class="dashboard-index fade-in">
    <div class="page-header">
        <h1 class="page-title mb-0">
            <i class="fas fa-chart-line me-3"></i>
            <?= Html::encode($this->title) ?>
        </h1>
        <p class="text-muted"><?= Yii::t('app', 'Welcome back! Here\'s what\'s happening with your education center today.') ?></p>
    </div>

    <div class="stats-cards">
        <a href="<?= Url::to(['/student/index']) ?>" class="stat-card-link" style="text-decoration: none;">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1"><?= Yii::t('app', 'Total Students') ?></h6>
                        <div class="stat-number"><?= $stats['totalStudents'] ?></div>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i> <?= Yii::t('app', '+5% from last month') ?></small>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-users text-primary fa-2x"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="<?= Url::to(['/teacher/index']) ?>" class="stat-card-link" style="text-decoration: none;">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1"><?= Yii::t('app', 'Total Teachers') ?></h6>
                        <div class="stat-number"><?= $stats['totalTeachers'] ?></div>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i> <?= Yii::t('app', '+2% from last month') ?></small>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-chalkboard-teacher text-success fa-2x"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="<?= Url::to(['/course/index']) ?>" class="stat-card-link" style="text-decoration: none;">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1"><?= Yii::t('app', 'Active Courses') ?></h6>
                        <div class="stat-number"><?= $stats['totalCourses'] ?></div>
                        <small class="text-success"><i class="fas fa-arrow-up me-1"></i> <?= Yii::t('app', '+8% from last month') ?></small>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-book text-info fa-2x"></i>
                    </div>
                </div>
            </div>
        </a>

        <a href="<?= Url::to(['/payment/index']) ?>" class="stat-card-link" style="text-decoration: none;">
            <div class="stat-card hover-lift">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase text-muted mb-1"><?= Yii::t('app', 'Monthly Income') ?></h6>
                        <div class="stat-number"><?= number_format($stats['monthlyIncome'], 0) ?></div>
                        <small class="text-muted"><?= Yii::t('app', 'UZS') ?></small>
                        <small class="text-success d-block"><i class="fas fa-arrow-up me-1"></i> <?= Yii::t('app', '+12% from last month') ?></small>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                        <i class="fas fa-dollar-sign text-warning fa-2x"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-primary text-dark-blue border-0 rounded-top">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        <?= Yii::t('app', 'Monthly Income Overview') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="incomeChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-dark-blue border-0 rounded-top">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        <?= Yii::t('app', 'Top Teachers') ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <?php foreach ($topTeachers as $index => $teacher): ?>
                            <a href="<?= Url::to(['/teacher/view', 'id' => $teacher->id]) ?>" class="list-group-item list-group-item-action border-0 py-3 hover-lift" style="text-decoration: none;">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-gradient-primary p-2 rounded-circle">
                                                <i class="fas fa-user-circle text-blue fa-lg"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= Html::encode($teacher->full_name) ?></h6>
                                            <small class="text-muted"><?= Yii::t('app', 'Teacher') ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-warning rounded-pill px-3 py-2">
                                            <i class="fas fa-star me-1"></i>
                                            <?= $teacher->rating ?>
                                        </span>
                                        <div class="mt-1">
                                            <small class="text-muted"><?= Yii::t('app', 'Rank') ?> #<?= $index + 1 ?></small>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-gradient-info text-blue border-0 rounded-top">
            <h5 class="card-title mb-0">
                <i class="fas fa-receipt me-2"></i>
                <?= Yii::t('app', 'Recent Payments') ?>
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-bold text-uppercase text-light small">ID</th>
                            <th class="fw-bold text-uppercase text-light small"><?= Yii::t('app', 'Student') ?></th>
                            <th class="fw-bold text-uppercase text-light small"><?= Yii::t('app', 'Course') ?></th>
                            <th class="fw-bold text-uppercase text-light small"><?= Yii::t('app', 'Amount') ?></th>
                            <th class="fw-bold text-uppercase text-light small"><?= Yii::t('app', 'Type') ?></th>
                            <th class="fw-bold text-uppercase text-light small"><?= Yii::t('app', 'Date') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPayments as $payment): ?>
                            <tr class="hover-lift" style="cursor: pointer;" onclick="window.location='<?= Url::to(['/payment/view', 'id' => $payment->id]) ?>'">
                                <td class="fw-bold text-primary"><?= $payment->id ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <div class="bg-gradient-primary p-2 rounded-circle">
                                                <i class="fas fa-user text-dark"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <strong><?= Html::encode($payment->student->full_name) ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td><?= Html::encode($payment->course->name) ?></td>
                                <td>
                                    <strong class="text-success"><?= number_format($payment->amount, 0) ?> <?= Yii::t('app', 'UZS') ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-gradient-<?= $payment->payment_type == 'monthly' ? 'primary' : 'success' ?> rounded-pill px-3 py-2">
                                        <i class="fas fa-<?= $payment->payment_type == 'monthly' ? 'calendar-alt' : 'credit-card' ?> me-1"></i>
                                        <?= Yii::t('app', ucfirst($payment->payment_type)) ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= Yii::$app->formatter->asDate($payment->payment_date) ?>
                                    </small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
// Chart.js ni yuklash
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);

// Tarjimalar uchun o'zgaruvchilar (JSON-safe)
$incomeLabel = json_encode(Yii::t('app', 'Income (UZS)'));
$incomePrefix = json_encode(Yii::t('app', 'Income:'));
$noDataText = json_encode(Yii::t('app', 'No data available'));
$chartErrorText = json_encode(Yii::t('app', 'Chart loading error'));

// JavaScript kodini registratsiya qilish
$this->registerJs("
(function() {
    'use strict';
    
    try {
        const chartElement = document.getElementById('incomeChart');
        
        if (!chartElement) {
            console.warn('Chart canvas element not found');
            return;
        }
        
        const ctx = chartElement.getContext('2d');
        
        if (!ctx) {
            console.error('Cannot get 2D context from canvas');
            return;
        }
        
        const monthlyData = " . json_encode($monthlyData ?? []) . ";
        
        if (!monthlyData || monthlyData.length === 0) {
            console.warn('No monthly data available for chart');
            const noDataMsg = '<p class=\"text-center text-muted py-5\"><i class=\"fas fa-chart-line fa-3x mb-3\"></i><br>' + " . $noDataText . " + '</p>';
            chartElement.parentElement.innerHTML = noDataMsg;
            return;
        }
        
        if (typeof Chart === 'undefined') {
            console.error('Chart.js library not loaded');
            return;
        }
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(d => d.month),
                datasets: [{
                    label: " . $incomeLabel . ",
                    data: monthlyData.map(d => d.income),
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return " . $incomePrefix . " + ' ' + context.parsed.y.toLocaleString() + ' UZS';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return (value / 1000000).toFixed(1) + 'M UZS';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        console.log('Income Chart initialized successfully');
        
    } catch (error) {
        console.error('Chart initialization error:', error);
        
        const chartElement = document.getElementById('incomeChart');
        if (chartElement && chartElement.parentElement) {
            const errorMsg = '<p class=\"text-center text-danger py-5\"><i class=\"fas fa-exclamation-triangle fa-3x mb-3\"></i><br>' + " . $chartErrorText . " + '</p>';
            chartElement.parentElement.innerHTML = errorMsg;
        }
    }
})();
", \yii\web\View::POS_READY);
?>