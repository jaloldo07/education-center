<?php

use yii\helpers\Html;
use common\models\Attendance;

$this->title = Yii::t('app', 'Attendance History') . ' - ' . $course->name;
?>

<style>
    /* ... BARCHA CSS STYLELAR O'ZGARISHSZ QOLADI ... */
    .attendance-history-page { padding: 40px 0; font-family: 'Nunito', sans-serif; }
    .page-header { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
    .page-title h1 { font-weight: 800; color: white; margin: 0; font-size: 2rem; text-shadow: 0 0 10px rgba(67, 97, 238, 0.5); }
    .group-info { color: rgba(255, 255, 255, 0.6); margin-top: 5px; font-size: 1rem; }
    .group-info strong { color: var(--accent-color); }
    .attendance-card { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; margin-bottom: 25px; overflow: hidden; transition: transform 0.3s ease; }
    .attendance-card:hover { transform: translateY(-5px); border-color: rgba(255, 255, 255, 0.2); box-shadow: 0 15px 40px rgba(0,0,0,0.4); }
    .card-date-header { background: rgba(255, 255, 255, 0.05); padding: 20px 25px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); color: white; display: flex; align-items: center; gap: 15px; }
    .calendar-icon { width: 45px; height: 45px; background: linear-gradient(135deg, #4361ee, #3a0ca3); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 0 15px rgba(67, 97, 238, 0.4); }
    .table-glass { width: 100%; color: white; margin-bottom: 0; }
    .table-glass th { background: rgba(0,0,0,0.2); color: rgba(255,255,255,0.5); font-weight: 700; text-transform: uppercase; font-size: 0.8rem; padding: 15px 25px; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-glass td { padding: 15px 25px; border-bottom: 1px solid rgba(255,255,255,0.05); vertical-align: middle; }
    .table-glass tr:last-child td { border-bottom: none; }
    .table-glass tr:hover td { background: rgba(255,255,255,0.05); }
    .badge-neon { padding: 6px 12px; border-radius: 8px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .bg-success { background: rgba(74, 222, 128, 0.2) !important; color: #4ade80 !important; border: 1px solid rgba(74, 222, 128, 0.3); }
    .bg-danger { background: rgba(248, 113, 113, 0.2) !important; color: #f87171 !important; border: 1px solid rgba(248, 113, 113, 0.3); }
    .bg-warning { background: rgba(251, 191, 36, 0.2) !important; color: #fbbf24 !important; border: 1px solid rgba(251, 191, 36, 0.3); }
    .bg-info { background: rgba(56, 189, 248, 0.2) !important; color: #38bdf8 !important; border: 1px solid rgba(56, 189, 248, 0.3); }
    .card-stats-footer { background: rgba(0, 0, 0, 0.2); padding: 20px; display: flex; justify-content: space-around; border-top: 1px solid rgba(255,255,255,0.1); }
    .stat-mini { text-align: center; }
    .stat-val { font-size: 1.2rem; font-weight: 800; color: white; display: block; }
    .stat-lbl { font-size: 0.8rem; text-transform: uppercase; font-weight: 600; opacity: 0.8; }
    .btn-glass-back { background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 10px 20px; border-radius: 12px; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .btn-glass-back:hover { background: white; color: black; }
    .empty-glass { text-align: center; padding: 60px; background: rgba(255,255,255,0.05); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.2); }
</style>

<div class="attendance-history-page">
    <div class="container">
        
        <div class="page-header animate__animated animate__fadeInDown">
            <div class="page-title">
                <h1><i class="fas fa-history text-info me-2"></i> <?= Yii::t('app', 'Attendance History') ?></h1>
                <p class="group-info">
                    <?= Yii::t('app', 'Course') ?>: <strong><?= Html::encode($course->name) ?></strong>
                </p>
            </div>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['attendance', 'id' => $course->id], ['class' => 'btn-glass-back']) ?>
            </div>
        </div>

        <?php if (empty($attendanceByDate)): ?>
            <div class="empty-glass animate__animated animate__fadeInUp">
                <i class="fas fa-calendar-times fa-4x mb-3 text-white-50"></i>
                <h4 class="text-white"><?= Yii::t('app', 'No attendance records yet') ?></h4>
                <p class="text-white-50"><?= Yii::t('app', 'Start taking attendance to see history here') ?></p>
            </div>
        <?php else: ?>
            
            <?php foreach ($attendanceByDate as $date => $records): 
                $present = count(array_filter($records, fn($r) => $r->status === 'present'));
                $absent = count(array_filter($records, fn($r) => $r->status === 'absent'));
                $late = count(array_filter($records, fn($r) => $r->status === 'late'));
                $excused = count(array_filter($records, fn($r) => $r->status === 'excused'));
            ?>
                <div class="attendance-card animate__animated animate__fadeInUp">
                    <div class="card-date-header">
                        <div class="calendar-icon">
                            <i class="fas fa-calendar-day text-white"></i>
                        </div>
                        <div>
                            <h5 class="m-0 fw-bold"><?= Yii::$app->formatter->asDate($date, 'php:l, F d, Y') ?></h5>
                            <small class="text-white-50"><?= count($records) ?> students recorded</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table-glass">
                            <thead>
                                <tr>
                                    <th width="50">#</th>
                                    <th><?= Yii::t('app', 'Student') ?></th>
                                    <th><?= Yii::t('app', 'Status') ?></th>
                                    <th><?= Yii::t('app', 'Note') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $i => $attendance): ?>
                                    <tr>
                                        <td><span class="text-white-50"><?= $i + 1 ?></span></td>
                                        <td>
                                            <span class="fw-bold"><?= Html::encode($attendance->student->full_name) ?></span>
                                        </td>
                                        <td>
                                            <span class="badge badge-neon bg-<?= Attendance::getStatusBadgeClass($attendance->status) ?>">
                                                <?php 
                                                    $icon = '';
                                                    switch($attendance->status){
                                                        case 'present': $icon = '✓'; break;
                                                        case 'absent': $icon = '✕'; break;
                                                        case 'late': $icon = '⏱'; break;
                                                        case 'excused': $icon = '☂'; break;
                                                    }
                                                ?>
                                                <?= $icon ?> <?= Yii::t('app', ucfirst($attendance->status)) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($attendance->note): ?>
                                                <span class="text-white-50 small"><i class="far fa-sticky-note me-1"></i> <?= Html::encode($attendance->note) ?></span>
                                            <?php else: ?>
                                                <span class="text-white-50">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="card-stats-footer">
                        <div class="stat-mini">
                            <span class="stat-val text-success"><?= $present ?></span>
                            <span class="stat-lbl text-success"><?= Yii::t('app', 'Present') ?></span>
                        </div>
                        <div class="stat-mini">
                            <span class="stat-val text-danger"><?= $absent ?></span>
                            <span class="stat-lbl text-danger"><?= Yii::t('app', 'Absent') ?></span>
                        </div>
                        <div class="stat-mini">
                            <span class="stat-val text-warning"><?= $late ?></span>
                            <span class="stat-lbl text-warning"><?= Yii::t('app', 'Late') ?></span>
                        </div>
                        <div class="stat-mini">
                            <span class="stat-val text-info"><?= $excused ?></span>
                            <span class="stat-lbl text-info"><?= Yii::t('app', 'Excused') ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>