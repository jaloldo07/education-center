<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $enrollments common\models\Enrollment[] */

$this->title = Yii::t('app', 'My Lessons');
?>

<style>
    /* 1. Umumiy Sahifa Stili */
    .my-lessons-page {
        padding: 30px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* Sarlavha */
    .page-heading {
        color: white;
        font-weight: 800;
        font-size: 2rem;
        margin-bottom: 30px;
        text-shadow: 0 0 10px rgba(67, 97, 238, 0.5);
    }

    /* 2. Glass Card */
    .lesson-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }

    .lesson-card:hover {
        background: rgba(255, 255, 255, 0.08);
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.5);
        border-color: var(--accent-color);
    }

    /* 3. Header Gradient */
    .card-header-gradient {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.8), rgba(63, 55, 201, 0.8));
        padding: 25px;
        position: relative;
    }

    .course-icon-bg {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
        color: white;
    }

    .lesson-course-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: white;
        z-index: 1;
        position: relative;
    }

    /* 4. Body */
    .card-body-custom {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        color: rgba(255, 255, 255, 0.8);
    }

    .info-row {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 0.95rem;
    }

    .info-icon {
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.1);
        color: var(--accent-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
    }

    /* 5. Neon Progress Bar */
    .progress-container {
        margin: 15px 0 25px 0;
        background: rgba(0, 0, 0, 0.2);
        padding: 15px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .progress-track {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4cc9f0, #4361ee);
        border-radius: 10px;
        box-shadow: 0 0 10px #4cc9f0;
    }

    /* 6. Button */
    .btn-start {
        background: white;
        color: var(--dark-color);
        border-radius: 50px;
        padding: 12px 0;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        width: 100%;
        text-align: center;
        display: block;
        margin-top: auto; /* Pastga taqash */
        text-decoration: none !important;
        transition: 0.3s;
    }

    .btn-start:hover {
        background: var(--accent-color);
        color: white;
        box-shadow: 0 0 20px var(--accent-color);
    }

    /* Empty State */
    .empty-box {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 60px 20px;
        text-align: center;
    }
</style>

<div class="my-lessons-page">
    <div class="container">
        <h2 class="page-heading">
            <i class="fas fa-graduation-cap mr-3 text-info"></i>
            <?= Yii::t('app', 'My Enrolled Courses') ?>
        </h2>

        <?php if (empty($enrollments)): ?>
            <div class="empty-box">
                <i class="fas fa-book-open fa-5x mb-4 text-white-50"></i>
                <h3 class="text-white fw-bold"><?= Yii::t('app', 'No Courses Yet!') ?></h3>
                <p class="text-white-50"><?= Yii::t('app', 'Enroll in courses to start your learning journey') ?></p>
                <?= Html::a(Yii::t('app', 'Browse Courses'), ['/site/courses'], ['class' => 'btn btn-outline-info rounded-pill px-4 mt-3']) ?>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($enrollments as $enrollment):
                    $course = $enrollment->course;
                    if (!$course) continue; // Agar kurs topilmasa xato bermasligi uchun
                    
                    $totalLessons = \common\models\Lesson::find()->where(['course_id' => $course->id, 'is_published' => 1])->count();
                    $completedLessons = \common\models\LessonProgress::find()->joinWith('lesson')->where(['lesson_progress.student_id' => $enrollment->student_id, 'lesson_progress.status' => 'completed', 'lesson.course_id' => $course->id])->count();
                    $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="lesson-card">
                            <div class="card-header-gradient">
                                <i class="fas fa-laptop-code course-icon-bg"></i>
                                <h3 class="lesson-course-title"><?= Html::encode($course->name) ?></h3>
                                <div class="mt-2 text-white-50 small">
                                    <i class="far fa-calendar-alt mr-1"></i> <?= Yii::t('app', 'Enrolled') ?>: <?= Yii::$app->formatter->asDate($enrollment->enrolled_on) ?>
                                </div>
                            </div>

                            <div class="card-body-custom">
                                <div class="info-row">
                                    <div class="info-icon"><i class="fas fa-chalkboard-teacher"></i></div>
                                    <div>
                                        <small class="text-white-50 d-block"><?= Yii::t('app', 'Teacher') ?></small>
                                        <span class="fw-bold text-white"><?= Html::encode($course->teacher->full_name) ?></span>
                                    </div>
                                </div>

                                <div class="progress-container">
                                    <div class="d-flex justify-content-between small text-white-50 mb-2">
                                        <span><?= Yii::t('app', 'Progress') ?></span>
                                        <span class="text-info fw-bold"><?= $progress ?>%</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <div class="text-end small text-white-50 mt-1">
                                        <?= Yii::t('app', '{completed} / {total} lessons', [
                                            'completed' => $completedLessons,
                                            'total' => $totalLessons
                                        ]) ?>
                                    </div>
                                </div>

                                <?= Html::a(
                                    '<i class="fas fa-play-circle mr-2"></i> ' . Yii::t('app', 'Continue'),
                                    ['course', 'course_id' => $course->id],
                                    ['class' => 'btn-start']
                                ) ?>

                                <?= Html::a(
                                    '<i class="fas fa-clipboard-check mr-2"></i> ' . Yii::t('app', 'Tests'),
                                    ['/student-test/index'], // Link
                                    [
                                        'class' => 'btn-start mt-2', // mt-2 orasi ochiq turishi uchun
                                        // Maxsus stil: Shaffof fon va oq hoshiya
                                        'style' => 'background: transparent; border: 1px solid rgba(255,255,255,0.3); color: white;'
                                    ]
                                ) ?>
                                
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>