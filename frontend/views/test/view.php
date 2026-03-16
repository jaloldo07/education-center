<?php
use yii\helpers\Html;
use common\models\Test;

$this->title = $model->title;
?>

<style>
    /* ... BARCHA CSS STYLARLAR O'ZGARISHSZ QOLADI ... */
    .test-view-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .glass-header { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); padding: 30px; color: white; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(67, 97, 238, 0.4); display: flex; justify-content: space-between; align-items: center; }
    .header-title h3 { font-weight: 800; margin: 0; font-size: 1.5rem; }
    .main-glass-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
    .card-title-section { padding: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; justify-content: space-between; align-items: flex-start; }
    .test-main-title { color: white; font-weight: 800; font-size: 2rem; margin-bottom: 10px; text-shadow: 0 0 15px rgba(67, 97, 238, 0.5); }
    .test-description { color: rgba(255,255,255,0.7); font-size: 1rem; line-height: 1.6; max-width: 800px; }
    .status-badge { padding: 8px 16px; border-radius: 12px; font-weight: 700; text-transform: uppercase; font-size: 0.9rem; letter-spacing: 1px; border: 1px solid transparent; }
    .status-active { background: rgba(74, 222, 128, 0.2); color: #4ade80; border-color: rgba(74, 222, 128, 0.3); }
    .status-draft { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border-color: rgba(251, 191, 36, 0.3); }
    .status-closed { background: rgba(248, 113, 113, 0.2); color: #f87171; border-color: rgba(248, 113, 113, 0.3); }
    .info-section { padding: 30px; }
    .info-glass-box { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 20px; height: 100%; transition: 0.3s; }
    .info-glass-box:hover { background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); transform: translateY(-5px); }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .info-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .info-label { color: rgba(255,255,255,0.5); font-size: 0.9rem; display: flex; align-items: center; gap: 8px; }
    .info-val { color: white; font-weight: 600; text-align: right; }
    .stats-row { display: flex; gap: 20px; margin-top: 30px; }
    .stat-mini-box { flex: 1; text-align: center; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 12px; }
    .stat-num { font-size: 1.5rem; font-weight: 800; color: #4cc9f0; display: block; }
    .stat-lbl { font-size: 0.8rem; text-transform: uppercase; color: rgba(255,255,255,0.5); }
    .schedule-glass-alert { background: rgba(67, 97, 238, 0.1); border: 1px solid rgba(67, 97, 238, 0.3); border-radius: 16px; padding: 20px; margin: 0 30px 30px 30px; display: flex; align-items: center; gap: 20px; }
    .schedule-icon { font-size: 2rem; color: #4361ee; background: rgba(67, 97, 238, 0.2); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .schedule-dates { display: flex; gap: 30px; color: rgba(255,255,255,0.8); }
    .date-item strong { color: white; display: block; font-size: 0.8rem; text-transform: uppercase; opacity: 0.5; margin-bottom: 2px; }
    .action-glass-footer { padding: 30px; background: rgba(0,0,0,0.2); border-top: 1px solid rgba(255,255,255,0.05); }
    .btn-action-glass { padding: 12px 20px; border-radius: 12px; font-weight: 600; border: none; display: flex; align-items: center; gap: 8px; justify-content: center; transition: 0.3s; text-decoration: none; width: 100%; }
    .btn-edit { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }
    .btn-edit:hover { background: #fbbf24; color: black; }
    .btn-manage { background: rgba(67, 97, 238, 0.2); color: #4cc9f0; border: 1px solid rgba(67, 97, 238, 0.3); }
    .btn-manage:hover { background: #4cc9f0; color: white; }
    .btn-results { background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .btn-results:hover { background: #4ade80; color: black; }
    .btn-delete { background: rgba(248, 113, 113, 0.2); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
    .btn-delete:hover { background: #f87171; color: white; }
    .btn-glass-back { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 8px 20px; border-radius: 12px; transition: 0.3s; text-decoration: none; }
    .btn-glass-back:hover { background: white; color: black; }
</style>

<div class="test-view-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="glass-header animate__animated animate__fadeInDown">
                    <div class="header-title">
                        <h3><i class="bi bi-clipboard-data me-2"></i> <?= Yii::t('app', 'Test Details') ?></h3>
                        <small style="opacity:0.7"><?= Yii::t('app', 'Comprehensive view') ?></small>
                    </div>
                    <?= Html::a('<i class="bi bi-arrow-left me-1"></i> ' . Yii::t('app', 'Back to Tests'), ['index'], ['class' => 'btn-glass-back']) ?>
                </div>

                <div class="main-glass-card animate__animated animate__fadeInUp">
                    
                    <div class="card-title-section">
                        <div>
                            <h2 class="test-main-title"><?= Html::encode($model->title) ?></h2>
                            <?php if ($model->description): ?>
                                <p class="test-description"><?= Html::encode($model->description) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <?php 
                        $statusClass = 'status-draft';
                        if ($model->status === 'active') $statusClass = 'status-active';
                        elseif ($model->status === 'closed') $statusClass = 'status-closed';
                        ?>
                        <span class="status-badge <?= $statusClass ?>">
                            <?= Yii::t('app', strtoupper($model->status)) ?>
                        </span>
                    </div>

                    <div class="info-section">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-glass-box">
                                    <div class="info-row">
                                        <span class="info-label"><i class="bi bi-book"></i> <?= Yii::t('app', 'Course') ?></span>
                                        <span class="info-val"><?= Html::encode($model->course->name ?? '-') ?></span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label"><i class="bi bi-person-badge"></i> <?= Yii::t('app', 'Teacher') ?></span>
                                        <span class="info-val"><?= Html::encode($model->teacher->full_name) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-glass-box">
                                    <div class="info-row">
                                        <span class="info-label"><i class="bi bi-clock"></i> <?= Yii::t('app', 'Duration') ?></span>
                                        <span class="info-val"><?= $model->duration ?> min</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label"><i class="bi bi-trophy"></i> <?= Yii::t('app', 'Pass Score') ?></span>
                                        <span class="info-val"><?= $model->passing_score ?>%</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label"><i class="bi bi-camera"></i> <?= Yii::t('app', 'Face ID') ?></span>
                                        <span class="info-val text-warning">
                                            <?= $model->require_face_control ? 'Required' : 'No' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="stats-row">
                            <div class="stat-mini-box">
                                <span class="stat-num"><?= $model->total_questions ?></span>
                                <span class="stat-lbl"><?= Yii::t('app', 'Questions') ?></span>
                            </div>
                            <div class="stat-mini-box">
                                <span class="stat-num"><?= $model->getTotalPoints() ?></span>
                                <span class="stat-lbl"><?= Yii::t('app', 'Total Points') ?></span>
                            </div>
                        </div>
                    </div>

                    <?php if ($model->start_date || $model->end_date): ?>
                        <div class="schedule-glass-alert">
                            <div class="schedule-icon"><i class="bi bi-calendar-range"></i></div>
                            <div class="schedule-dates">
                                <?php if ($model->start_date): ?>
                                    <div class="date-item">
                                        <strong><?= Yii::t('app', 'Start Date') ?></strong>
                                        <?= Yii::$app->formatter->asDatetime($model->start_date) ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($model->end_date): ?>
                                    <div class="date-item">
                                        <strong><?= Yii::t('app', 'End Date') ?></strong>
                                        <?= Yii::$app->formatter->asDatetime($model->end_date) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="action-glass-footer">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <?= Html::a('<i class="bi bi-pencil"></i> ' . Yii::t('app', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn-action-glass btn-edit']) ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <?= Html::a('<i class="bi bi-list-check"></i> ' . Yii::t('app', 'Questions'), ['manage-questions', 'id' => $model->id], ['class' => 'btn-action-glass btn-manage']) ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <?= Html::a('<i class="bi bi-bar-chart"></i> ' . Yii::t('app', 'Results'), ['results', 'id' => $model->id], ['class' => 'btn-action-glass btn-results']) ?>
                            </div>
                            <div class="col-md-3 mb-2">
                                <?= Html::a('<i class="bi bi-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                                    'class' => 'btn-action-glass btn-delete',
                                    'data' => [
                                        'confirm' => Yii::t('app', 'Are you sure you want to delete this test?'),
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