<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Test;

$this->title = Yii::t('app', 'My Tests');
?>

<style>
    /* 1. Page Container */
    .test-list-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Glass Header */
    .glass-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .header-title h2 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    /* 3. Test Cards (Glass) */
    .test-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 30px;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .test-glass-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    }

    /* Card Top Gradient */
    .card-top-gradient {
        height: 6px;
        width: 100%;
        background: linear-gradient(90deg, #4361ee, #f72585);
    }

    /* Card Body */
    .card-body-glass {
        padding: 25px;
        flex-grow: 1;
        color: white;
    }

    .test-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.4;
    }
    .test-title a { color: white; text-decoration: none; transition: 0.2s; }
    .test-title a:hover { color: #4cc9f0; }

    /* Meta Info */
    .test-meta {
        font-size: 0.9rem;
        color: rgba(255,255,255,0.6);
        margin-bottom: 20px;
        display: flex; flex-wrap: wrap; gap: 15px;
    }
    .meta-item { display: flex; align-items: center; gap: 6px; }
    .meta-item i { color: #4cc9f0; }

    /* Stats Grid inside card */
    .card-stats-grid {
        display: flex;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 15px;
        justify-content: space-between;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    
    .stat-item { text-align: center; }
    .stat-val { font-weight: 800; font-size: 1.1rem; color: white; display: block; }
    .stat-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.5); text-transform: uppercase; }

    /* Status Badges */
    .status-badge {
        position: absolute;
        top: 20px; right: 20px;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        border: 1px solid transparent;
    }
    .status-active { background: rgba(74, 222, 128, 0.2); color: #4ade80; border-color: rgba(74, 222, 128, 0.3); }
    .status-draft { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border-color: rgba(251, 191, 36, 0.3); }
    .status-closed { background: rgba(248, 113, 113, 0.2); color: #f87171; border-color: rgba(248, 113, 113, 0.3); }

    /* Footer Actions */
    .card-footer-glass {
        padding: 15px 25px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .action-group { display: flex; gap: 8px; }

    .btn-glass-icon {
        width: 35px; height: 35px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.7);
        border: 1px solid rgba(255,255,255,0.1);
        transition: 0.2s;
        text-decoration: none;
    }
    .btn-glass-icon:hover { color: white; background: rgba(255,255,255,0.15); transform: translateY(-2px); }
    
    .btn-manage { color: #4cc9f0; border-color: rgba(76, 201, 240, 0.3); background: rgba(76, 201, 240, 0.1); }
    .btn-manage:hover { background: #4cc9f0; color: white; }

    /* Buttons */
    .btn-create-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 12px 30px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-create-neon:hover {
        transform: translateY(-2px);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 12px 25px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-glass-back:hover { background: white; color: black; }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 80px;
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        border: 1px dashed rgba(255,255,255,0.2);
    }

</style>

<div class="test-list-page">
    <div class="container">
        
        <div class="glass-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h2><i class="bi bi-folder2-open text-primary me-2"></i> <?= Yii::t('app', 'My Tests') ?></h2>
            </div>
            <div class="d-flex gap-3">
                <?= Html::a('<i class="bi bi-plus-lg me-1"></i> ' . Yii::t('app', 'Create Test'), ['create'], ['class' => 'btn-create-neon']) ?>
                <?= Html::a('<i class="bi bi-arrow-left me-1"></i> ' . Yii::t('app', 'Lessons'), ['/lesson/index'], ['class' => 'btn-glass-back']) ?>
            </div>
        </div>

        <?php if (empty($tests)): ?>
            <div class="empty-glass animate__animated animate__fadeInUp">
                <i class="bi bi-clipboard-x fa-4x mb-3 text-white-50"></i>
                <h4 class="text-white"><?= Yii::t('app', 'No tests found') ?></h4>
                <p class="text-white-50 mb-4"><?= Yii::t('app', 'Create your first test to assess your students.') ?></p>
                <?= Html::a(Yii::t('app', 'Create New Test'), ['create'], ['class' => 'btn-create-neon']) ?>
            </div>
        <?php else: ?>
            
            <div class="row">
                <?php foreach ($tests as $test): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="test-glass-card animate__animated animate__fadeInUp">
                            <div class="card-top-gradient"></div>
                            
                            <?php 
                            $statusClass = 'status-draft';
                            $statusIcon = 'bi-pencil-square';
                            if ($test->status === Test::STATUS_ACTIVE) { $statusClass = 'status-active'; $statusIcon = 'bi-check-circle'; }
                            elseif ($test->status === Test::STATUS_CLOSED) { $statusClass = 'status-closed'; $statusIcon = 'bi-lock'; }
                            ?>
                            <span class="status-badge <?= $statusClass ?>">
                                <i class="bi <?= $statusIcon ?> me-1"></i> <?= Yii::t('app', ucfirst($test->status)) ?>
                            </span>

                            <div class="card-body-glass">
                                <div class="test-title">
                                    <?= Html::encode($test->title) ?>
                                </div>

                                <div class="test-meta">
                                    <div class="meta-item" title="<?= Yii::t('app', 'Course') ?>">
                                        <i class="bi bi-book"></i> <?= Html::encode($test->course->name ?? Yii::t('app', 'No Course')) ?>
                                    </div>
                                    <?php if ($test->group): ?>
                                        <div class="meta-item" title="<?= Yii::t('app', 'Group') ?>">
                                            <i class="bi bi-people"></i> <?= Html::encode($test->group->name) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-stats-grid">
                                    <div class="stat-item">
                                        <span class="stat-val"><?= $test->duration ?></span>
                                        <span class="stat-lbl"><?= Yii::t('app', 'Mins') ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-val"><?= $test->total_questions ?></span>
                                        <span class="stat-lbl"><?= Yii::t('app', 'Questions') ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <span class="stat-val"><?= $test->passing_score ?>%</span>
                                        <span class="stat-lbl"><?= Yii::t('app', 'Pass') ?></span>
                                    </div>
                                </div>

                                <?php if ($test->require_face_control): ?>
                                    <div class="text-white-50 small mb-2">
                                        <i class="bi bi-camera-video me-1 text-warning"></i> <?= Yii::t('app', 'Face Control Enabled') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="card-footer-glass">
                                <?= Html::a('<i class="bi bi-list-check me-2"></i> ' . Yii::t('app', 'Questions'), ['manage-questions', 'id' => $test->id], [
                                    'class' => 'btn-glass-icon btn-manage', 
                                    'style' => 'width: auto; padding: 0 15px; font-weight: 600;',
                                    'title' => Yii::t('app', 'Manage Questions')
                                ]) ?>

                                <div class="action-group">
                                    <?= Html::a('<i class="bi bi-bar-chart"></i>', ['results', 'id' => $test->id], [
                                        'class' => 'btn-glass-icon',
                                        'title' => Yii::t('app', 'View Results')
                                    ]) ?>
                                    <?= Html::a('<i class="bi bi-pencil-square"></i>', ['update', 'id' => $test->id], [
                                        'class' => 'btn-glass-icon',
                                        'title' => Yii::t('app', 'Edit Settings')
                                    ]) ?>
                                    <?= Html::a('<i class="bi bi-trash3"></i>', ['delete', 'id' => $test->id], [
                                        'class' => 'btn-glass-icon text-danger',
                                        'title' => Yii::t('app', 'Delete'),
                                        'data' => [
                                            'confirm' => Yii::t('app', 'Are you sure you want to delete this test?'),
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>
</div>