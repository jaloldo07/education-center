<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Schedule;

$this->title = Yii::t('app', 'Manage Schedule') . ' - ' . $course->name;
?>

<style>
    /* ... OLDINGI BARCHA CSS STYLELAR O'ZGARISHSZ QOLADI ... */
    .schedule-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .page-header { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .header-title h1 { font-weight: 800; color: white; margin: 0; font-size: 2rem; text-shadow: 0 0 15px rgba(67, 97, 238, 0.6); }
    .header-subtitle { color: rgba(255, 255, 255, 0.6); margin-top: 5px; font-size: 1rem; }
    .header-subtitle strong { color: var(--accent-color); }
    .table-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; overflow: hidden; margin-bottom: 30px; }
    .table-header-glass { padding: 20px 25px; background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1); color: white; font-size: 1.2rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .table-glass { width: 100%; color: white; margin: 0; }
    .table-glass th { padding: 15px 20px; background: rgba(0,0,0,0.2); color: #4cc9f0; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-glass td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .table-glass tr:last-child td { border-bottom: none; }
    .table-glass tr:hover td { background: rgba(255,255,255,0.05); }
    .badge-glass { padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; display: inline-block; }
    .badge-info { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .badge-success { background: rgba(74, 222, 128, 0.2); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .badge-secondary { background: rgba(148, 163, 184, 0.2); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.3); }
    .stat-glass-box { background: rgba(0, 0, 0, 0.3); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 20px; text-align: center; transition: 0.3s; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; }
    .stat-glass-box:hover { transform: translateY(-5px); background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.2); }
    .stat-val { font-size: 2rem; font-weight: 800; color: white; line-height: 1.2; }
    .stat-lbl { font-size: 0.9rem; color: rgba(255,255,255,0.6); text-transform: uppercase; }
    .text-neon-blue { color: #4361ee; text-shadow: 0 0 10px rgba(67, 97, 238, 0.4); }
    .text-neon-green { color: #4ade80; text-shadow: 0 0 10px rgba(74, 222, 128, 0.4); }
    .btn-glass-action { width: 35px; height: 35px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: 0.3s; text-decoration: none; }
    .btn-danger-glass { background: rgba(248, 113, 113, 0.2); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
    .btn-danger-glass:hover { background: #f87171; color: white; box-shadow: 0 0 10px rgba(248, 113, 113, 0.5); }
    .btn-neon { border: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-neon-primary { background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; box-shadow: 0 0 15px rgba(67, 97, 238, 0.4); }
    .btn-neon-primary:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(67, 97, 238, 0.6); color: white; }
    .btn-glass-secondary { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .btn-glass-secondary:hover { background: white; color: black; }
    .btn-glass-info { background: rgba(76, 201, 240, 0.2); color: #4cc9f0; border: 1px solid rgba(76, 201, 240, 0.3); }
    .btn-glass-info:hover { background: #4cc9f0; color: white; }
    .empty-glass { text-align: center; padding: 60px; background: rgba(255,255,255,0.05); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.2); }
</style>

<div class="schedule-page">
    <div class="container">
        
        <div class="page-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h1><i class="fas fa-calendar-alt text-warning me-2"></i> <?= Yii::t('app', 'Class Schedule') ?></h1>
                <div class="header-subtitle">
                    <?= Yii::t('app', 'Course') ?>: <strong><?= Html::encode($course->name) ?></strong>
                </div>
            </div>
            <div class="d-flex gap-2">
                <?= Html::a('<i class="fas fa-calendar me-1"></i> ' . Yii::t('app', 'Calendar'), ['calendar'], ['class' => 'btn-neon btn-glass-info']) ?>
                <?= Html::a('<i class="fas fa-plus me-1"></i> ' . Yii::t('app', 'Add'), ['create-schedule', 'id' => $course->id], ['class' => 'btn-neon btn-neon-primary']) ?>
                <?= Html::a('<i class="fas fa-arrow-left me-1"></i> ' . Yii::t('app', 'Back'), ['/teacher/dashboard'],  ['class' => 'btn-neon btn-glass-secondary']) ?>
            </div>
        </div>

        <?php if (empty($schedules)): ?>
            <div class="empty-glass animate__animated animate__fadeInUp">
                <i class="fas fa-calendar-plus fa-4x mb-3 text-white-50"></i>
                <h4 class="text-white"><?= Yii::t('app', 'No schedule created yet') ?></h4>
                <p class="text-white-50 mb-4"><?= Yii::t('app', 'Create a class schedule to organize your teaching time') ?></p>
                <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Create First Schedule'), ['create-schedule', 'id' => $course->id], ['class' => 'btn-neon btn-neon-primary']) ?>
            </div>
        <?php else: ?>
            
            <div class="table-card animate__animated animate__fadeInUp">
                <div class="table-header-glass">
                    <i class="fas fa-calendar-week text-info"></i> <?= Yii::t('app', 'Weekly Schedule') ?>
                </div>
                
                <div class="table-responsive">
                    <table class="table-glass">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Day') ?></th>
                                <th><?= Yii::t('app', 'Time') ?></th>
                                <th><?= Yii::t('app', 'Duration') ?></th>
                                <th><?= Yii::t('app', 'Room') ?></th>
                                <th><?= Yii::t('app', 'Status') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schedules as $schedule): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-calendar-day text-primary"></i>
                                            <span class="fw-bold text-white"><?= Yii::t('app', $schedule->getDayName()) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-white-50">
                                            <i class="far fa-clock me-1 text-success"></i>
                                            <?= date('H:i', strtotime($schedule->start_time)) ?> - <?= date('H:i', strtotime($schedule->end_time)) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-glass badge-info">
                                            <?= $schedule->getDuration() ?> <?= Yii::t('app', 'hours') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-white"><?= Html::encode($schedule->room ?: Yii::t('app', 'Not specified')) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-glass badge-success"><?= Yii::t('app', 'Active') ?></span>
                                    </td>
                                    <td class="text-end">
                                        <?= Html::a('<i class="fas fa-trash"></i>', ['delete-schedule', 'id' => $schedule->id], [
                                            'class' => 'btn-glass-action btn-danger-glass',
                                            'data-confirm' => Yii::t('app', 'Are you sure?'),
                                            'data-method' => 'post',
                                            'title' => Yii::t('app', 'Delete')
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="col-md-6 mb-3">
                    <?= Html::a(
                        '<div class="stat-glass-box">
                            <div class="stat-val text-neon-blue">' . count($schedules) . '</div>
                            <div class="stat-lbl">' . Yii::t('app', 'Classes per Week') . '</div>
                        </div>',
                        ['calendar'],
                        ['style' => 'text-decoration: none; display: block; height: 100%;']
                    ) ?>
                </div>
                <div class="col-md-6 mb-3">
                    <?= Html::a(
                        '<div class="stat-glass-box">
                            <div class="stat-val text-neon-green">' . array_sum(array_map(fn($s) => $s->getDuration(), $schedules)) . '</div>
                            <div class="stat-lbl">' . Yii::t('app', 'Total Hours/Week') . '</div>
                        </div>',
                        ['calendar'],
                        ['style' => 'text-decoration: none; display: block; height: 100%;']
                    ) ?>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>