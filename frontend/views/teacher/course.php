<?php
use yii\helpers\Html;

$this->title = Yii::t('app', 'Course') . ': ' . $course->name;

// Faqat faol a'zolarni ajratib olamiz
$activeEnrollments = [];
if (!empty($course->enrollments)) {
    $activeEnrollments = array_filter($course->enrollments, function($e) {
        return $e->status === 'active';
    });
}
?>

<style>
    /* 1. Container */
    .teacher-course-view {
        padding: 40px 0 80px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Page Header */
    .page-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .header-title h1 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .course-label {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        font-size: 1rem;
    }

    /* 3. Info Cards (Glass) */
    .info-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 30px;
        color: white;
    }

    .card-title-glass {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        padding-bottom: 15px;
        margin-bottom: 20px;
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #4cc9f0;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .info-row:last-child { border-bottom: none; }

    .info-label { color: rgba(255,255,255,0.5); }
    .info-val { font-weight: 600; color: white; }

    /* 4. Student Table */
    .table-glass-container {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
    }

    .table-glass {
        width: 100%;
        color: white;
        margin: 0;
    }

    .table-glass th {
        background: rgba(67, 97, 238, 0.2);
        padding: 15px 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .table-glass td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .table-glass tr:hover td {
        background: rgba(255,255,255,0.05);
    }

    /* Buttons */
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
        padding: 50px;
        color: rgba(255,255,255,0.5);
    }

</style>

<div class="teacher-course-view">
    <div class="container">
        
        <div class="page-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h1><i class="fas fa-book-open text-primary me-2"></i> <?= Html::encode($course->name) ?></h1>
                <div class="course-label">
                    <?= Yii::t('app', 'Teacher') ?>: <span class="text-white fw-bold"><?= Html::encode($teacher->full_name) ?></span>
                </div>
            </div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['/teacher/dashboard'], ['class' => 'btn-glass-back']) ?>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="info-glass-card animate__animated animate__fadeInLeft">
                    <div class="card-title-glass">
                        <i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Course Details') ?>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label"><?= Yii::t('app', 'Duration') ?></span>
                        <span class="info-val"><?= $course->duration ?> <?= Yii::t('app', 'months') ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><?= Yii::t('app', 'Price') ?></span>
                        <span class="info-val text-success"><?= number_format($course->price, 0) ?> UZS</span>
                    </div>
                    <div class="info-row border-0">
                        <span class="info-label"><?= Yii::t('app', 'Active Students') ?></span>
                        <span class="info-val badge bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                            <?= count($activeEnrollments) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="table-glass-container animate__animated animate__fadeInRight">
                    <div class="card-title-glass p-3 mb-0 border-bottom">
                        <i class="fas fa-user-graduate"></i> <?= Yii::t('app', 'Enrolled Students') ?>
                    </div>

                    <?php if (empty($activeEnrollments)): ?>
                        <div class="empty-glass">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p><?= Yii::t('app', 'No active students enrolled in this course yet.') ?></p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table-glass">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th><?= Yii::t('app', 'Full Name') ?></th>
                                        <th><?= Yii::t('app', 'Contact') ?></th>
                                        <th><?= Yii::t('app', 'Enrolled') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $i = 1;
                                    foreach ($activeEnrollments as $enrollment): 
                                        $student = $enrollment->student;
                                    ?>
                                        <tr>
                                            <td class="text-white-50"><?= $i++ ?></td>
                                            <td>
                                                <div class="fw-bold"><?= Html::encode($student->full_name) ?></div>
                                                <small class="text-white-50"><?= Html::encode($student->email) ?></small>
                                            </td>
                                            <td>
                                                <span class="text-info"><i class="fas fa-phone-alt me-1"></i> <?= Html::encode($student->phone) ?></span>
                                            </td>
                                            <td class="text-white-50">
                                                <?= Yii::$app->formatter->asDate($enrollment->enrolled_on) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>