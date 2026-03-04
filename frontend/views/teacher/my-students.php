<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'My Students');
?>

<style>
    /* 1. Page Container */
    .my-students-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header */
    .page-header {
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

    .page-title h1 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.6);
    }

    /* 3. Student Glass Card */
    .student-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        transition: 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .student-glass-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
        background: rgba(255, 255, 255, 0.08);
    }

    /* Card Top Gradient Line */
    .card-top-line {
        height: 4px;
        background: linear-gradient(90deg, #4361ee, #f72585);
        width: 100%;
    }

    /* Card Body */
    .card-body-glass {
        padding: 25px;
        flex-grow: 1;
    }

    /* Avatar */
    .avatar-circle {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #1e293b, #0f172a);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        border: 2px solid rgba(255,255,255,0.1);
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        margin-right: 20px;
    }

    .student-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: white;
        margin: 0;
    }

    .student-email {
        color: rgba(255,255,255,0.5);
        font-size: 0.9rem;
    }

    /* Info Items */
    .info-item {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .info-icon {
        width: 30px;
        text-align: center;
        margin-right: 10px;
        font-size: 1rem;
    }

    .text-val { color: rgba(255,255,255,0.9); font-weight: 500; }
    .text-lbl { color: rgba(255,255,255,0.5); margin-right: 5px; }

    /* Badges */
    .badge-glass {
        background: rgba(67, 97, 238, 0.2);
        color: #4cc9f0;
        border: 1px solid rgba(67, 97, 238, 0.3);
        padding: 5px 10px;
        border-radius: 8px;
        font-size: 0.75rem;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }

    /* Card Footer Actions */
    .card-footer-glass {
        background: rgba(0,0,0,0.2);
        padding: 15px 25px;
        border-top: 1px solid rgba(255,255,255,0.05);
        display: flex;
        gap: 10px;
    }

    .btn-action-glass {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        padding: 8px 0;
        border-radius: 10px;
        text-align: center;
        font-size: 0.9rem;
        text-decoration: none;
        transition: 0.3s;
    }
    .btn-action-glass:hover {
        background: white;
        color: black;
    }

    /* Back Button */
    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-glass-back:hover {
        background: white; color: black;
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

<div class="my-students-page">
    <div class="container">
        
        <div class="page-header animate__animated animate__fadeInDown">
            <div class="page-title">
                <h1>
                    <i class="fas fa-user-graduate text-warning me-2"></i> 
                    <?= Yii::t('app', 'My Students') ?> 
                    <span class="fs-4 text-white-50">(<?= $totalStudents ?>)</span>
                </h1>
            </div>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to Dashboard'), ['dashboard'], ['class' => 'btn-glass-back']) ?>
            </div>
        </div>

        <?php if (empty($students)): ?>
            <div class="empty-glass animate__animated animate__fadeInUp">
                <i class="fas fa-user-slash fa-4x mb-3 text-white-50"></i>
                <h4 class="text-white"><?= Yii::t('app', 'No students yet') ?></h4>
                <p class="mb-0"><?= Yii::t('app', 'Students will appear here when they enroll in your groups') ?></p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($students as $data): ?>
                    <?php $student = $data['student']; ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="student-glass-card animate__animated animate__fadeInUp">
                            <div class="card-top-line"></div>
                            
                            <div class="card-body-glass">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="avatar-circle">
                                        <?= strtoupper(substr($student->full_name, 0, 1)) ?>
                                    </div>
                                    <div>
                                        <h5 class="student-name"><?= Html::encode($student->full_name) ?></h5>
                                        <div class="student-email"><?= Html::encode($student->email) ?></div>
                                    </div>
                                </div>

                                <div class="info-list">
                                    <div class="info-item">
                                        <div class="info-icon text-success"><i class="fas fa-phone-alt"></i></div>
                                        <div>
                                            <span class="text-lbl"><?= Yii::t('app', 'Phone') ?>:</span>
                                            <span class="text-val"><?= Html::encode($student->phone) ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-icon text-info"><i class="far fa-calendar-alt"></i></div>
                                        <div>
                                            <span class="text-lbl"><?= Yii::t('app', 'Enrolled') ?>:</span>
                                            <span class="text-val"><?= Yii::$app->formatter->asDate($student->enrolled_date) ?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3 pt-3 border-top border-secondary border-opacity-25">
                                        <div class="d-flex align-items-start">
                                            <div class="info-icon text-warning pt-1"><i class="fas fa-layer-group"></i></div>
                                            <div>
                                                <div class="text-lbl mb-1"><?= Yii::t('app', 'Groups') ?>:</div>
                                                <div>
                                                    <?php foreach ($data['groups'] as $group): ?>
                                                        <span class="badge-glass">
                                                            <?= Html::encode($group->name) ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer-glass">
                                <a href="mailto:<?= $student->email ?>" class="btn-action-glass">
                                    <i class="fas fa-envelope me-1"></i> Email
                                </a>
                                <a href="tel:<?= $student->phone ?>" class="btn-action-glass">
                                    <i class="fas fa-phone me-1"></i> Call
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>