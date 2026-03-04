<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Test Results') . ' - ' . $test->title;
?>

<style>
    /* 1. Page Container */
    .results-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header (Test Info Card) */
    .test-info-glass-card {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .test-info-glass-card::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        filter: blur(40px);
    }

    .test-title h3 {
        font-weight: 800;
        margin-bottom: 10px;
        font-size: 1.8rem;
    }

    .test-meta {
        display: flex; gap: 20px;
        font-size: 0.95rem;
        color: rgba(255,255,255,0.8);
    }
    .meta-item i { margin-right: 5px; color: #4cc9f0; }

    /* 3. Stats Cards */
    .stat-glass-box {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        transition: 0.3s;
    }
    .stat-glass-box:hover {
        transform: translateY(-5px);
        background: rgba(255,255,255,0.05);
        border-color: rgba(255,255,255,0.2);
    }

    .stat-number { font-size: 2rem; font-weight: 800; line-height: 1.2; margin-bottom: 5px; }
    .stat-label { font-size: 0.85rem; text-transform: uppercase; color: rgba(255,255,255,0.5); letter-spacing: 1px; }

    .text-neon-blue { color: #4361ee; text-shadow: 0 0 10px rgba(67, 97, 238, 0.4); }
    .text-neon-green { color: #4ade80; text-shadow: 0 0 10px rgba(74, 222, 128, 0.4); }
    .text-neon-red { color: #f87171; text-shadow: 0 0 10px rgba(248, 113, 113, 0.4); }
    .text-neon-purple { color: #c084fc; text-shadow: 0 0 10px rgba(192, 132, 252, 0.4); }

    /* 4. Results Table Card */
    .results-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
    }

    .card-header-glass {
        padding: 20px 25px;
        background: rgba(255,255,255,0.05);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex; align-items: center; gap: 10px;
    }

    .table-glass {
        width: 100%;
        color: white;
        margin: 0;
    }

    .table-glass th {
        padding: 15px 20px;
        background: rgba(0,0,0,0.2);
        color: rgba(255,255,255,0.6);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .table-glass td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .table-glass tr:hover td { background: rgba(255,255,255,0.05); }

    .student-name { font-weight: 700; color: white; display: block; }
    .student-email { font-size: 0.85rem; color: rgba(255,255,255,0.5); }

    /* Badges */
    .score-badge {
        padding: 6px 12px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.9rem;
        display: inline-block;
        min-width: 60px;
        text-align: center;
    }
    .score-passed { background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .score-failed { background: rgba(248, 113, 113, 0.2); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }

    .status-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        font-weight: 700;
    }
    .status-pass { color: #4ade80; background: rgba(74, 222, 128, 0.1); }
    .status-fail { color: #f87171; background: rgba(248, 113, 113, 0.1); }

    /* Buttons */
    .btn-view-glass {
        width: 35px; height: 35px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(67, 97, 238, 0.2);
        color: #4cc9f0;
        border: 1px solid rgba(67, 97, 238, 0.3);
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-view-glass:hover { background: #4cc9f0; color: white; transform: translateY(-2px); }


    .btn-glass-light {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 8px 20px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        transition: 0.3s;
        z-index: 1000; 
    }
    .btn-glass-light:hover { background: white; color: #4361ee; }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 60px;
        color: rgba(255,255,255,0.5);
    }

</style>

<div class="results-page">
    <div class="container">
        
        <div class="test-info-glass-card animate__animated animate__fadeInDown">
            <div class="d-flex justify-content-between align-items-center">
                <div class="test-title">
                    <h3><i class="bi bi-clipboard-data-fill me-2"></i> <?= Html::encode($test->title) ?></h3>
                    <div class="test-meta">
                        <span class="meta-item"><i class="bi bi-calendar3"></i> <?= Yii::t('app', 'Created') ?>: <?= Yii::$app->formatter->asDate($test->created_at) ?></span>
                        <span class="meta-item"><i class="bi bi-list-ol"></i> <?= $test->total_questions ?> <?= Yii::t('app', 'Questions') ?></span>
                        <span class="meta-item"><i class="bi bi-trophy"></i> <?= Yii::t('app', 'Pass Score') ?>: <?= $test->passing_score ?>%</span>
                    </div>
                </div>
                <?= Html::a('<i class="bi bi-arrow-left me-2"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn-glass-light']) ?>
            </div>
        </div>

        <?php if (!empty($attempts)): ?>
            <div class="row mb-4 animate__animated animate__fadeInUp">
                <div class="col-md-3">
                    <div class="stat-glass-box">
                        <div class="stat-number text-neon-blue"><?= count($attempts) ?></div>
                        <div class="stat-label"><?= Yii::t('app', 'Total Attempts') ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-glass-box">
                        <div class="stat-number text-neon-green">
                            <?= count(array_filter($attempts, function($a) { return $a->isPassed(); })) ?>
                        </div>
                        <div class="stat-label"><?= Yii::t('app', 'Passed') ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-glass-box">
                        <div class="stat-number text-neon-red">
                            <?= count(array_filter($attempts, function($a) { return !$a->isPassed(); })) ?>
                        </div>
                        <div class="stat-label"><?= Yii::t('app', 'Failed') ?></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-glass-box">
                        <div class="stat-number text-neon-purple">
                            <?php 
                            $avg = count($attempts) > 0 
                                ? round(array_sum(array_map(function($a) { return $a->score; }, $attempts)) / count($attempts), 1)
                                : 0;
                            echo $avg . '%';
                            ?>
                        </div>
                        <div class="stat-label"><?= Yii::t('app', 'Avg Score') ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="results-glass-card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card-header-glass">
                <i class="bi bi-people-fill text-info"></i> <?= Yii::t('app', 'Student Attempts') ?>
            </div>

            <?php if (empty($attempts)): ?>
                <div class="empty-glass">
                    <i class="bi bi-inbox fa-3x mb-3 opacity-50"></i>
                    <p><?= Yii::t('app', 'No attempts yet for this test.') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table-glass">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="30%"><?= Yii::t('app', 'Student') ?></th>
                                <th width="15%" class="text-center"><?= Yii::t('app', 'Score') ?></th>
                                <th width="10%" class="text-center"><?= Yii::t('app', 'Status') ?></th>
                                <th width="15%"><?= Yii::t('app', 'Points') ?></th>
                                <th width="15%"><?= Yii::t('app', 'Time') ?></th>
                                <th width="10%" class="text-end"><?= Yii::t('app', 'Action') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attempts as $index => $attempt): ?>
                                <tr>
                                    <td class="text-white-50"><?= $index + 1 ?></td>
                                    <td>
                                        <span class="student-name"><?= Html::encode($attempt->student->full_name) ?></span>
                                        <span class="student-email"><?= Html::encode($attempt->student->email) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="score-badge <?= $attempt->isPassed() ? 'score-passed' : 'score-failed' ?>">
                                            <?= $attempt->score ?>%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($attempt->isPassed()): ?>
                                            <span class="status-badge status-pass"><?= Yii::t('app', 'Pass') ?></span>
                                        <?php else: ?>
                                            <span class="status-badge status-fail"><?= Yii::t('app', 'Fail') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-bold text-white-50">
                                        <?= $attempt->points_earned ?> / <?= $attempt->total_points ?>
                                    </td>
                                    <td class="text-white-50 small">
                                        <i class="bi bi-calendar me-1"></i> <?= Yii::$app->formatter->asDate($attempt->finished_at) ?><br>
                                        <i class="bi bi-clock me-1"></i> <?= Yii::$app->formatter->asRelativeTime($attempt->finished_at) ?>
                                    </td>
                                    <td class="text-end">
                                        <?= Html::a('<i class="bi bi-eye-fill"></i>', ['view-attempt', 'id' => $attempt->id], [
                                            'class' => 'btn-view-glass',
                                            'title' => Yii::t('app', 'View Details')
                                        ]) ?>
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