<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $course common\models\Course */

$this->title = $course->name;
$isFree = $course->isFree();
// Talaba ekanligini tekshiramiz
$isStudent = !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'student';
?>

<style>
    /* Hero Section - Shaffof */
    .course-hero {
        padding: 60px 0;
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 40px;
        background: linear-gradient(to right, rgba(67, 97, 238, 0.2), transparent);
        position: relative;
    }
    
    /* Asosiy Glass Blok */
    .glass-panel {
        background: rgba(15, 23, 42, 0.6); /* To'q fon */
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 30px;
        color: #e2e8f0;
        margin-bottom: 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        color: #4ade80; /* Yashilroq rang */
        font-weight: 700;
        margin-bottom: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 10px;
        font-size: 1.5rem;
    }

    /* Sidebar Glass */
    .sidebar-glass {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        position: sticky;
        top: 100px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .price-big {
        font-size: 2.2rem;
        font-weight: 800;
        color: white;
        text-shadow: 0 0 20px rgba(255,255,255,0.2);
    }

    /* Neon Button */
    .btn-neon {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 16px;
        width: 100%;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.4);
        transition: all 0.3s ease;
        display: block;
        text-decoration: none;
    }
    
    .btn-neon:hover {
        background: linear-gradient(135deg, #3a0ca3, #4361ee);
        box-shadow: 0 0 25px rgba(67, 97, 238, 0.8);
        color: white;
        transform: translateY(-2px);
    }

    .instructor-avatar {
        width: 70px; height: 70px;
        background: linear-gradient(45deg, #4361ee, #4cc9f0);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: white;
        border: 3px solid rgba(255,255,255,0.2);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
</style>

<div class="course-hero">
    <div class="container">
        <?php if ($isFree): ?>
            <span class="badge bg-success mb-3 px-3 py-2 fs-6">
                <i class="fas fa-lock-open me-1"></i> <?= Yii::t('app', 'OCHIQ QABUL') ?>
            </span>
        <?php else: ?>
            <span class="badge bg-warning text-dark mb-3 px-3 py-2 fs-6">
                <i class="fas fa-crown me-1"></i> <?= Yii::t('app', 'PREMIUM / TASDIQLASH KERAK') ?>
            </span>
        <?php endif; ?>

        <h1 class="display-4 font-weight-bold text-white"><?= Html::encode($course->name) ?></h1>
        
        <div class="d-flex gap-4 mt-4 opacity-75 text-white">
            <span><i class="fas fa-clock text-info me-1"></i> <?= $course->duration ?> <?= Yii::t('app', 'Oy') ?></span>
            <span><i class="fas fa-signal text-success me-1"></i> <?= Yii::t('app', 'Barcha darajalar') ?></span>
            <span><i class="fas fa-globe text-warning me-1"></i> <?= Yii::t('app', 'Online') ?></span>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="glass-panel">
                <h3 class="section-title"><i class="fas fa-info-circle me-2"></i> <?= Yii::t('app', 'Kurs haqida') ?></h3>
                <div style="line-height: 1.8; opacity: 0.9; font-size: 1.05rem;">
                    <?= nl2br(Html::encode($course->description)) ?>
                </div>
            </div>

            <div class="glass-panel">
                <h3 class="section-title"><i class="fas fa-chalkboard-teacher me-2"></i> <?= Yii::t('app', 'O\'qituvchi') ?></h3>
                <div class="d-flex align-items-center gap-4 mb-4">
                    <div class="instructor-avatar">
                        <?= strtoupper(substr($course->teacher->full_name, 0, 1)) ?>
                    </div>
                    <div>
                        <h4 class="mb-1 text-white fw-bold"><?= Html::encode($course->teacher->full_name) ?></h4>
                        <span class="badge bg-primary bg-opacity-50"><?= Html::encode($course->teacher->subject) ?></span>
                    </div>
                </div>
                <?php if (!empty($course->teacher->bio)): ?>
                    <div class="p-3 rounded bg-dark bg-opacity-25 border border-secondary border-opacity-25">
                        <p class="text-white-50 fst-italic mb-0">"<?= nl2br(Html::encode($course->teacher->bio)) ?>"</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sidebar-glass">
                <h6 class="text-uppercase text-white-50 mb-3 fw-bold"><?= Yii::t('app', 'Kurs narxi') ?></h6>
                
                <div class="price-big mb-1">
                    <?= number_format($course->price, 0, '.', ' ') ?> 
                    <span class="fs-6"><?= Yii::t('app', 'so\'m') ?></span>
                </div>
                <div class="text-white-50 mb-4 small"><?= Yii::t('app', 'To\'liq kursga kirish') ?></div>

                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('<i class="fas fa-sign-in-alt me-2"></i> ' . Yii::t('app', 'Kirish va a\'zo bo\'lish'), ['/site/login'], ['class' => 'btn-neon']) ?>
                
                <?php elseif ($isStudent): ?>
                    
                    <?php if ($isFree): ?>
                        <?= Html::a('<i class="fas fa-rocket me-2"></i> ' . Yii::t('app', 'O\'qishni boshlash'), ['site/enroll', 'id' => $course->id], ['class' => 'btn-neon']) ?>
                    <?php else: ?>
                        <?= Html::a('<i class="fas fa-paper-plane me-2"></i> ' . Yii::t('app', 'Ariza topshirish'), ['site/enroll', 'id' => $course->id], ['class' => 'btn-neon']) ?>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="alert alert-dark border-light text-center">
                        <i class="fas fa-user-lock mb-2 fs-4 d-block"></i>
                        <?= Yii::t('app', 'Faqat talabalar a\'zo bo\'lishi mumkin') ?>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4 text-start text-white-50 small">
                    <div class="py-2 border-bottom border-secondary border-opacity-25"><i class="fas fa-check text-success me-2"></i> <?= Yii::t('app', 'To\'liq kirish') ?></div>
                    <div class="py-2 border-bottom border-secondary border-opacity-25"><i class="fas fa-check text-success me-2"></i> <?= Yii::t('app', 'Manba fayllari') ?></div>
                    <div class="py-2"><i class="fas fa-check text-success me-2"></i> <?= Yii::t('app', 'Sertifikat') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>