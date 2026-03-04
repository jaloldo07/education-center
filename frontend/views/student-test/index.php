<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\TestAttempt;

$this->title = Yii::t('app', 'Mavjud Testlar');
?>

<style>
    /* 1. Page Container */
    .student-test-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header Gradient */
    .glass-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .header-title h2 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        font-size: 1rem;
    }

    /* 3. Test Cards (Glass) */
    .test-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 30px;
        transition: transform 0.3s ease;
    }

    .test-glass-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 20px 50px rgba(0,0,0,0.4);
    }

    /* Card Header */
    .test-card-header {
        background: linear-gradient(135deg, #7209b7 0%, #3a0ca3 100%);
        padding: 20px 25px;
        color: white;
    }

    .test-card-header h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .course-label {
        font-size: 0.85rem;
        color: rgba(255,255,255,0.7);
        display: block;
        margin-top: 5px;
    }

    /* Card Body */
    .test-card-body {
        padding: 25px;
        color: white;
    }

    .test-desc {
        color: rgba(255,255,255,0.6);
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    /* Info Rows */
    .test-info-row {
        display: flex;
        align-items: center;
        padding: 10px;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        margin-bottom: 10px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .info-icon {
        width: 35px; height: 35px;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px;
        font-size: 1.1rem;
    }
    
    .icon-questions { color: #4cc9f0; }
    .icon-time { color: #f72585; }
    .icon-score { color: #4ade80; }

    .info-text strong { color: white; }
    .info-text { color: rgba(255,255,255,0.6); font-size: 0.9rem; }

    /* Attempts Badge */
    .attempts-badge {
        display: inline-block;
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: bold;
        margin-bottom: 15px;
        border: 1px solid rgba(251, 191, 36, 0.4);
    }
    
    .attempts-badge.limit-reached {
        background: rgba(248, 113, 113, 0.2);
        color: #f87171;
        border-color: rgba(248, 113, 113, 0.4);
    }

    /* Footer & Button */
    .test-card-footer {
        padding: 20px 25px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.05);
    }

    a.btn-start-neon,
    a.btn-start-neon:hover,
    a.btn-start-neon:focus,
    a.btn-start-neon:active,
    a.btn-start-neon:visited {
        text-decoration: none !important;
        border-bottom: none !important;
        outline: none !important;
        box-shadow: 0 0 15px rgba(74, 222, 128, 0.3) !important;
    }
    
    a.btn-start-neon:hover {
        box-shadow: 0 0 25px rgba(74, 222, 128, 0.5) !important;
        transform: translateY(-2px);
    }
    .btn-start-neon {
        background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
        color: #064e3b;
        border: none;
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        text-align: center;
        transition: 0.3s;
    }

    .btn-locked {
        background: rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        text-align: center;
        cursor: not-allowed;
    }

    /* 4. Attempts Sidebar */
    .attempts-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 25px;
        position: sticky;
        top: 20px;
    }

    .sidebar-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .attempt-item {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #4361ee;
        transition: 0.3s;
    }
    .attempt-item:hover {
        background: rgba(255,255,255,0.08);
        transform: translateX(5px);
    }

    .attempt-meta {
        display: flex; justify-content: space-between; align-items: flex-start;
        margin-bottom: 5px;
    }
    .attempt-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        line-height: 1.3;
    }
    .attempt-score {
        font-size: 1.2rem;
        font-weight: 800;
        color: #4ade80;
    }
    .attempt-score.failed { color: #f87171; }

    .attempt-footer {
        display: flex; justify-content: space-between; align-items: center;
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
    }

    .btn-view-sm {
        background: rgba(67, 97, 238, 0.2);
        color: #4cc9f0;
        padding: 4px 10px;
        border-radius: 6px;
        text-decoration: none;
        transition: 0.2s;
    }
    .btn-view-sm:hover { background: #4cc9f0; color: white; }

    /* Alerts */
    .alert-glass-warning {
        background: rgba(251, 191, 36, 0.15);
        border: 1px solid rgba(251, 191, 36, 0.3);
        color: #fde68a;
        border-radius: 12px;
        padding: 10px;
        font-size: 0.9rem;
        margin-top: 15px;
    }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 60px;
        background: rgba(255,255,255,0.05);
        border-radius: 20px;
        border: 1px dashed rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.5);
    }

</style>

<div class="student-test-page">
    <div class="container">
        
        <div class="glass-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h2><i class="bi bi-clipboard-check text-warning me-2"></i> <?= Yii::t('app', 'Mavjud Testlar') ?></h2>
                <div class="header-subtitle"><?= Yii::t('app', 'Boshlash uchun testni tanlang yoki oldingi urinishlarni ko\'ring') ?></div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <?php if (empty($tests)): ?>
                    <div class="empty-glass animate__animated animate__fadeInUp">
                        <i class="bi bi-inbox fa-3x mb-3 opacity-50"></i>
                        <h4 class="text-white"><?= Yii::t('app', 'Testlar mavjud emas') ?></h4>
                        <p class="mb-0"><?= Yii::t('app', 'Hozircha faol testlar yo\'q') ?></p>
                    </div>
                <?php else: ?>
                    <?php foreach ($tests as $test): 
                        // 🔥 TALABANING SHU TESTDAGI URINISHLAR SONINI HISOBLASH
                        $studentAttemptCount = TestAttempt::find()
                            ->where(['test_id' => $test->id, 'student_id' => $student->id])
                            ->count();
                        
                        $maxAttempts = (int)$test->max_attempts;
                        $isLocked = ($maxAttempts > 0 && $studentAttemptCount >= $maxAttempts);
                    ?>
                        <div class="test-glass-card animate__animated animate__fadeInUp">
                            <div class="test-card-header">
                                <h4><?= Html::encode($test->title) ?></h4>
                                <span class="course-label"><i class="bi bi-book me-1"></i> <?= Html::encode($test->course->name ?? 'N/A') ?></span>
                            </div>

                            <div class="test-card-body">
                                
                                <div class="attempts-badge <?= $isLocked ? 'limit-reached' : '' ?>">
                                    <i class="bi bi-arrow-repeat me-1"></i> 
                                    <?= Yii::t('app', 'Urinishlar:') ?> 
                                    <strong><?= $studentAttemptCount ?></strong> / <?= $maxAttempts > 0 ? $maxAttempts : Yii::t('app', 'Cheksiz') ?>
                                </div>

                                <?php if ($test->description): ?>
                                    <p class="test-desc"><?= Html::encode($test->description) ?></p>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="test-info-row">
                                            <div class="info-icon icon-questions"><i class="bi bi-question-circle"></i></div>
                                            <div class="info-text">
                                                <strong><?= $test->total_questions ?></strong><br><?= Yii::t('app', 'Savollar') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="test-info-row">
                                            <div class="info-icon icon-time"><i class="bi bi-clock"></i></div>
                                            <div class="info-text">
                                                <strong><?= $test->duration ?></strong> <?= Yii::t('app', 'daqiqa') ?><br><?= Yii::t('app', 'Vaqt') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="test-info-row">
                                            <div class="info-icon icon-score"><i class="bi bi-trophy"></i></div>
                                            <div class="info-text">
                                                <strong><?= $test->passing_score ?>%</strong><br><?= Yii::t('app', 'O\'tish balli') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($test->require_face_control): ?>
                                    <div class="alert-glass-warning">
                                        <i class="bi bi-camera me-2"></i> <strong><?= Yii::t('app', 'Face Control:') ?></strong> <?= Yii::t('app', 'Boshlashdan oldin rasmga tushish kerak.') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="test-card-footer">
                                <?php if ($isLocked): ?>
                                    <div class="btn-locked">
                                        <i class="bi bi-lock-fill me-2"></i> <?= Yii::t('app', 'Limit tugadi') ?>
                                    </div>
                                <?php else: ?>
                                    <?= Html::a('<i class="bi bi-play-circle me-2"></i> ' . Yii::t('app', $studentAttemptCount > 0 ? 'Qayta ishlash' : 'Testni Boshlash'), ['start', 'id' => $test->id], ['class' => 'btn-start-neon']) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="col-lg-4">
                <div class="attempts-glass-card animate__animated animate__fadeInRight">
                    <div class="sidebar-title">
                        <i class="bi bi-clock-history me-2 text-info"></i> <?= Yii::t('app', 'Oxirgi Urinishlar') ?>
                    </div>

                    <?php if (empty($attempts)): ?>
                        <p class="text-center text-white-50 py-4 mb-0">
                            <?= Yii::t('app', 'Hali urinishlar yo\'q') ?>
                        </p>
                    <?php else: ?>
                        <?php foreach ($attempts as $attempt): ?>
                            <div class="attempt-item">
                                <div class="attempt-meta">
                                    <div class="attempt-title"><?= Html::encode($attempt->test->title) ?></div>
                                    <div class="attempt-score <?= $attempt->isPassed() ? '' : 'failed' ?>">
                                        <?= $attempt->score ?>%
                                    </div>
                                </div>
                                <div class="attempt-footer">
                                    <span><i class="bi bi-calendar me-1"></i> <?= Yii::$app->formatter->asRelativeTime($attempt->finished_at) ?></span>
                                    
                                    <?= Html::a(Yii::t('app', 'Ko\'rish'), ['result', 'id' => $attempt->id], ['class' => 'btn-view-sm']) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>