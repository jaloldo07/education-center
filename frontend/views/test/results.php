<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Test Results - ' . $test->title;
?>

<style>
    .results-page {
        background: #f8f9ff;
        min-height: 100vh;
        padding: 2rem 0;
    }

    .test-info-card {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(65, 79, 222, 0.3);
    }

    .results-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        padding: 1rem;
    }

    .attempt-row {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }

    .attempt-row:hover {
        background: #f8f9ff;
        transform: translateX(5px);
    }

    .score-badge {
        font-size: 1.2rem;
        font-weight: 700;
        padding: 0.5rem 1rem;
        border-radius: 12px;
    }

    .score-passed {
        background: linear-gradient(135deg, #4caf50, #45a049);
        color: white;
    }

    .score-failed {
        background: linear-gradient(135deg, #f44336, #e53935);
        color: white;
    }

    .btn-view {
        background: linear-gradient(135deg, #414fde, #6b74ff);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(65, 79, 222, 0.4);
        color: white;
    }

    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        color: #414fde;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #999;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }
</style>

<div class="results-page">
    <div class="container">
        <!-- Test Info -->
        <div class="test-info-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-2">
                        <i class="bi bi-clipboard-check"></i> <?= Html::encode($test->title) ?>
                    </h3>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar"></i> Created: <?= Yii::$app->formatter->asDatetime($test->created_at) ?>
                        | <i class="bi bi-list-ol"></i> <?= $test->total_questions ?> Questions
                        | <i class="bi bi-trophy"></i> Passing Score: <?= $test->passing_score ?>%
                    </p>
                </div>
                <?= Html::a('<i class="bi bi-arrow-left"></i> Back to Tests', ['index'], [
                    'class' => 'btn btn-light'
                ]) ?>
            </div>
        </div>

        <!-- Statistics -->
        <?php if (!empty($attempts)): ?>
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number"><?= count($attempts) ?></div>
                        <small class="text-muted">Total Attempts</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number text-success">
                            <?= count(array_filter($attempts, function($a) { return $a->isPassed(); })) ?>
                        </div>
                        <small class="text-muted">Passed</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number text-danger">
                            <?= count(array_filter($attempts, function($a) { return !$a->isPassed(); })) ?>
                        </div>
                        <small class="text-muted">Failed</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">
                            <?php 
                            $avg = count($attempts) > 0 
                                ? round(array_sum(array_map(function($a) { return $a->score; }, $attempts)) / count($attempts), 1)
                                : 0;
                            echo $avg;
                            ?>%
                        </div>
                        <small class="text-muted">Average Score</small>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Attempts List -->
        <div class="results-card">
            <div class="table-header">
                <h5 class="mb-0">
                    <i class="bi bi-people"></i> Student Attempts
                </h5>
            </div>

            <?php if (empty($attempts)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h5>No Attempts Yet</h5>
                    <p class="text-muted">Students haven't taken this test yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="25%">Student</th>
                                <th width="15%" class="text-center">Score</th>
                                <th width="10%" class="text-center">Status</th>
                                <th width="15%">Points</th>
                                <th width="20%">Submitted</th>
                                <th width="10%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attempts as $index => $attempt): ?>
                                <tr class="attempt-row">
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <div class="fw-bold"><?= Html::encode($attempt->student->full_name) ?></div>
                                                <small class="text-muted"><?= Html::encode($attempt->student->email) ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="score-badge <?= $attempt->isPassed() ? 'score-passed' : 'score-failed' ?>">
                                            <?= $attempt->score ?>%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($attempt->isPassed()): ?>
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Passed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle"></i> Failed
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= $attempt->points_earned ?></strong> / <?= $attempt->total_points ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-calendar"></i> 
                                        <?= Yii::$app->formatter->asDatetime($attempt->finished_at) ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> 
                                            <?= Yii::$app->formatter->asRelativeTime($attempt->finished_at) ?>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <?= Html::a(
                                            '<i class="bi bi-eye"></i> View',
                                            ['view-attempt', 'id' => $attempt->id],
                                            ['class' => 'btn btn-view btn-sm']
                                        ) ?>
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