<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'All Courses');
?>

<style>
    /* Glass Card Stili */
    .course-card {
        background: rgba(255, 255, 255, 0.05); /* Shaffof fon */
        backdrop-filter: blur(10px); /* Xiralashtirish */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative; /* Badge uchun kerak */
    }

    .course-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.5); /* Katta qora soya */
        border-color: var(--accent-color);
    }

    /* 🔥 YANGI: BADGE (PREMIUM/FREE YORLIG'I) */
    .course-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }

    .badge-premium {
        background: linear-gradient(135deg, #ffb703, #fb8500);
        color: #000;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-free {
        background: linear-gradient(135deg, #2ec4b6, #20bf55);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* Header qismi */
    .course-header {
        background: rgba(255, 255, 255, 0.03);
        padding: 35px 20px 25px; /* Tepadan sal ko'proq joy ochildi badge uchun */
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .course-icon {
        color: var(--accent-color);
        font-size: 40px;
        margin-bottom: 10px;
        text-shadow: 0 0 15px rgba(76, 201, 240, 0.5); /* Neon glow */
    }

    .course-title {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 0;
        font-size: 1.2rem;
        color: white;
    }

    /* Body qismi */
    .course-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        color: rgba(255, 255, 255, 0.8); /* Oqish matn */
    }

    .course-description {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 20px;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding-bottom: 8px;
    }

    .info-label { color: rgba(255, 255, 255, 0.5); font-size: 0.9rem; }
    .info-value { font-weight: 600; color: white; }

    /* Narx */
    .price-text {
        color: var(--success-color);
        font-size: 1.5rem;
        font-weight: 800;
        text-shadow: 0 0 10px rgba(74, 222, 128, 0.3);
    }
    
    /* Buttonlar */
    .btn-glass {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50px;
        padding: 10px 0;
        transition: 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    
    .btn-glass:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 15px var(--primary-color);
    }
</style>

<div class="courses-page py-5">
    <div class="container">
        <h1 class="mb-5 font-weight-bold text-white text-center">
            <i class="fas fa-book mr-2 text-info"></i> <?= Yii::t('app', 'Available Courses') ?>
        </h1>

        <div class="row">
            <?php foreach ($courses as $course): ?>
                <?php $isFree = $course->isFree(); ?>
                
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="course-card">
                        
                        <?php if ($isFree): ?>
                            <div class="course-badge badge-free">
                                <i class="fas fa-lock-open me-1"></i> Free
                            </div>
                        <?php else: ?>
                            <div class="course-badge badge-premium">
                                <i class="fas fa-crown me-1"></i> Premium
                            </div>
                        <?php endif; ?>

                        <div class="course-header">
                            <div class="course-icon"><i class="fas fa-book-open"></i></div>
                            <h5 class="course-title"><?= Html::encode($course->name) ?></h5>
                        </div>

                        <div class="course-body">
                            <p class="course-description">
                                <?= Html::encode(\yii\helpers\StringHelper::truncate($course->description, 80)) ?>
                            </p>
                            
                            <div class="info-row">
                                <span class="info-label"><i class="fas fa-chalkboard-teacher mr-2"></i><?= Yii::t('app', 'Teacher') ?></span>
                                <span class="info-value"><?= Html::encode($course->teacher->full_name) ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label"><i class="far fa-clock mr-2"></i><?= Yii::t('app', 'Duration') ?></span>
                                <span class="info-value"><?= $course->duration ?> <?= Yii::t('app', 'months') ?></span>
                            </div>

                            <div class="text-center mt-3 mb-3">
                                <div class="price-text">
                                    <?= number_format($course->price, 0, '.', ' ') ?> 
                                    <small style="font-size: 0.8rem;">UZS</small>
                                </div>
                            </div>

                            <div class="mt-auto">
                                <?= Html::a(
                                    Yii::t('app', 'View Details'),
                                    ['course-detail', 'id' => $course->id],
                                    ['class' => 'btn-glass w-100']
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>