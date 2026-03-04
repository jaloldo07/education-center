<?php
use yii\helpers\Html;
$this->title = Yii::t('app', 'Our Teachers');
?>

<style>
    /* Card Wrapper */
    .teacher-card-wrapper {
        text-decoration: none !important;
        display: block;
        height: 100%;
        color: inherit;
    }

    /* Glass Card */
    .teacher-glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-bottom: 20px;
    }

    .teacher-card-wrapper:hover .teacher-glass-card {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.08);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        border-color: var(--accent-color);
    }

    /* Banner */
    .card-banner-neon {
        height: 100px;
        width: 100%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        margin-bottom: 50px; /* Avatar uchun joy */
        position: relative;
    }

    /* Avatar */
    .avatar-neon {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: #1e293b; /* To'q fon */
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; font-weight: bold;
        border: 4px solid #1e293b;
        box-shadow: 0 0 20px rgba(67, 97, 238, 0.5);
        position: absolute;
        bottom: -50px;
        left: 50%;
        transform: translateX(-50%);
    }

    /* Text */
    .teacher-name {
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .teacher-subject {
        color: var(--accent-color);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }

    /* Rating Badge */
    .rating-glass {
        background: rgba(255, 255, 255, 0.1);
        padding: 5px 15px;
        border-radius: 20px;
        margin-bottom: 10px;
        color: #fbbf24; /* Sariq */
    }
    
    .exp-text {
        color: rgba(255,255,255,0.5);
        font-size: 0.85rem;
    }
</style>

<div class="teachers-page py-5">
    <div class="container">
        <h1 class="mb-5 font-weight-bold text-white text-center">
            <i class="fas fa-chalkboard-teacher mr-2 text-info"></i> <?= Yii::t('app', 'Meet Our Expert Teachers') ?>
        </h1>
        
        <div class="row">
            <?php foreach ($teachers as $teacher): ?>
                <div class="col-lg-3 col-md-6 mb-5">
                    <?= Html::a(
                        '<div class="teacher-glass-card">
                            <div class="card-banner-neon">
                                <div class="avatar-neon">
                                    ' . strtoupper(substr($teacher->full_name, 0, 1)) . '
                                </div>
                            </div>

                            <div class="text-center px-3">
                                <h5 class="teacher-name">' . Html::encode($teacher->full_name) . '</h5>
                                <p class="teacher-subject">' . Html::encode($teacher->subject) . '</p>

                                <div class="rating-glass">
                                    ' . str_repeat('<i class="fas fa-star"></i>', floor($teacher->rating)) . '
                                    <span class="fw-bold text-white ms-1">' . $teacher->rating . '</span>
                                </div>

                                <div class="exp-text">
                                    <i class="fas fa-briefcase me-1"></i>
                                    ' . $teacher->experience_years . ' ' . Yii::t('app', 'years experience') . '
                                </div>
                            </div>
                        </div>',
                        ['teacher-detail', 'id' => $teacher->id],
                        ['class' => 'teacher-card-wrapper']
                    ) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>