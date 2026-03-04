<?php
use yii\helpers\Html;

$this->title = $teacher->full_name;
?>

<style>
    /* Umumiy Sahifa */
    .teacher-detail-page {
        padding-top: 40px;
        font-family: 'Nunito', sans-serif;
    }

    /* 1. GLASS PANEL (Umumiy karta stili) */
    .glass-panel {
        background: rgba(255, 255, 255, 0.05); /* Shaffof qora */
        backdrop-filter: blur(15px); /* Xiralashtirish */
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        color: white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        margin-bottom: 30px;
        overflow: hidden;
    }

    /* 2. PROFILE SIDEBAR (Chap tomon) */
    .profile-card {
        text-align: center;
        position: sticky;
        top: 100px; /* Navbar balandligini hisobga olib */
        z-index: 10;
    }

    /* Neon Avatar */
    .profile-avatar {
        width: 140px;
        height: 140px;
        margin: 0 auto 20px;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        font-weight: 800;
        color: white;
        border: 4px solid rgba(67, 97, 238, 0.5); /* Neon border */
        box-shadow: 0 0 25px rgba(67, 97, 238, 0.4); /* Neon glow */
        position: relative;
    }

    .profile-name {
        font-weight: 800;
        font-size: 1.8rem;
        margin-bottom: 5px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }

    .profile-subject {
        color: #4cc9f0; /* Neon moviy */
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    /* Contact Info Row */
    .contact-row {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-bottom: 12px;
        padding: 10px;
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        transition: 0.3s;
    }
    .contact-row:hover {
        background: rgba(255,255,255,0.1);
        transform: translateX(5px);
    }
    .contact-icon {
        width: 35px; height: 35px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px;
        color: var(--accent-color);
    }

    /* 3. RIGHT CONTENT */
    .section-header {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 15px;
        margin-bottom: 20px;
        display: flex; align-items: center;
    }
    .section-title {
        font-size: 1.5rem; font-weight: 700; margin: 0;
        color: white;
    }
    .section-icon {
        font-size: 1.5rem; margin-right: 10px;
        color: #fbbf24; /* Sariq */
    }

    .bio-text {
        color: rgba(255,255,255,0.8);
        line-height: 1.8;
        font-size: 1.05rem;
    }

    /* 4. COURSE MINI CARDS */
    .mini-course-card {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .mini-course-card:hover {
        transform: translateY(-5px);
        border-color: #4cc9f0;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        background: rgba(255, 255, 255, 0.05);
    }

    .mini-card-body {
        padding: 20px;
        flex-grow: 1;
    }

    .mini-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: white;
    }

    .price-badge {
        font-weight: 700;
        font-size: 1.1rem;
        color: #4ade80; /* Yashil */
    }

    /* Buttons */
    .btn-back {
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.7);
        width: 100%;
        padding: 10px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        display: block;
        text-align: center;
    }
    .btn-back:hover {
        background: white;
        color: #000;
    }

    .btn-view {
        background: linear-gradient(90deg, #4361ee, #4cc9f0);
        color: white;
        border: none;
        width: 100%;
        padding: 8px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 15px;
        transition: 0.3s;
    }
    .btn-view:hover {
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
        color: white;
    }

</style>

<div class="teacher-detail-page">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-4">
                <div class="glass-panel profile-card">
                    
                    <div class="profile-avatar">
                        <?= strtoupper(substr($teacher->full_name, 0, 1)) ?>
                    </div>
                    
                    <h2 class="profile-name"><?= Html::encode($teacher->full_name) ?></h2>
                    <div class="profile-subject"><?= Html::encode($teacher->subject) ?></div>

                    <div class="mb-4">
                        <?php for($i = 0; $i < 5; $i++): ?>
                            <?php if($i < floor($teacher->rating)): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php else: ?>
                                <i class="far fa-star text-muted"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                        <span class="ms-2 text-white fw-bold"><?= $teacher->rating ?></span>
                    </div>

                    <hr style="border-color: rgba(255,255,255,0.1);">

                    <div class="text-start">
                        <div class="contact-row">
                            <div class="contact-icon"><i class="fas fa-award"></i></div>
                            <div>
                                <small class="text-white-50 d-block"><?= Yii::t('app', 'Experience') ?></small>
                                <span class="fw-bold"><?= $teacher->experience_years ?> <?= Yii::t('app', 'years') ?></span>
                            </div>
                        </div>
                        
                        <div class="contact-row">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <small class="text-white-50 d-block">Email</small>
                                <span class="text-white"><?= Html::encode($teacher->email) ?></span>
                            </div>
                        </div>

                        <div class="contact-row">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <small class="text-white-50 d-block">Phone</small>
                                <span class="text-white"><?= Html::encode($teacher->phone) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <?= Html::a('<i class="fas fa-arrow-left me-2"></i> ' . Yii::t('app', 'Back to Teachers'), ['/site/teachers'], ['class' => 'btn-back']) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                
                <div class="glass-panel">
                    <div class="section-header">
                        <i class="fas fa-user-tie section-icon"></i>
                        <h3 class="section-title"><?= Yii::t('app', 'About Instructor') ?></h3>
                    </div>
                    <div class="bio-text">
                        <?= nl2br(Html::encode($teacher->bio)) ?>
                    </div>
                </div>

                <div class="glass-panel">
                    <div class="section-header">
                        <i class="fas fa-book section-icon" style="color: #4cc9f0;"></i>
                        <h3 class="section-title">
                            <?= Yii::t('app', 'Courses by {name}', ['name' => Html::encode(explode(' ', $teacher->full_name)[0])]) ?>
                        </h3>
                    </div>

                    <?php if (empty($courses)): ?>
                        <div class="text-center py-4 text-white-50">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-50"></i>
                            <p><?= Yii::t('app', 'No courses available yet.') ?></p>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($courses as $course): ?>
                            <div class="col-md-6 mb-4">
                                <div class="mini-course-card">
                                    <div class="mini-card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="badge <?= $course->isFree() ? 'bg-success' : 'bg-warning text-dark' ?>">
                                                <?= strtoupper($course->type) ?>
                                            </span>
                                            <small class="text-white-50"><i class="fas fa-clock"></i> <?= $course->duration ?> mo</small>
                                        </div>
                                        
                                        <h5 class="mini-title"><?= Html::encode($course->name) ?></h5>
                                        <p class="small text-white-50 mb-3" style="min-height: 40px;">
                                            <?= Html::encode(\yii\helpers\StringHelper::truncate($course->description, 60)) ?>
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center border-top border-secondary pt-3">
                                            <span class="price-badge"><?= number_format($course->price, 0) ?> <small>UZS</small></span>
                                        </div>

                                        <?= Html::a(Yii::t('app', 'View Details'), ['course-detail', 'id' => $course->id], ['class' => 'btn-view']) ?>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>