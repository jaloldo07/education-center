<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Welcome to Education Center');

// Current user check
$isGuest = Yii::$app->user->isGuest;
$user = !$isGuest ? Yii::$app->user->identity : null;
$role = $user ? $user->role : null;
?>

<style>
    /* 1. Umumiy Sahifa */
    .homepage { padding: 60px 0; font-family: 'Nunito', sans-serif; }
    /* 2. Katta Glass Hero (Umumiy Qobiq) */
    .glass-hero { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 30px; padding: 50px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5); color: white; overflow: hidden; position: relative; }
    .glass-hero::before { content: ''; position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(67, 97, 238, 0.3) 0%, transparent 70%); filter: blur(50px); z-index: 0; }
    .hero-content { position: relative; z-index: 1; }
    /* 3. Typography */
    .hero-title { font-weight: 800; font-size: 3rem; margin-bottom: 20px; background: linear-gradient(90deg, #fff, #a5b4fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; text-shadow: 0 0 30px rgba(67, 97, 238, 0.5); }
    .hero-subtitle { font-size: 1.2rem; color: rgba(255, 255, 255, 0.7); max-width: 700px; margin: 0 auto 40px auto; }
    /* 4. Role Cards (Guest View) */
    .role-card { display: block; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px 20px; text-align: center; text-decoration: none; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); color: white; position: relative; overflow: hidden; }
    .role-card:hover { transform: translateY(-10px); background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.3); color: white; }
    .role-card.teacher:hover { box-shadow: 0 0 30px rgba(247, 37, 133, 0.4); border-color: #f72585; }
    .role-card.teacher .role-icon { color: #f72585; }
    .role-card.student:hover { box-shadow: 0 0 30px rgba(67, 97, 238, 0.4); border-color: #4361ee; }
    .role-card.student .role-icon { color: #4361ee; }
    .role-icon { font-size: 3.5rem; margin-bottom: 20px; transition: 0.3s; text-shadow: 0 0 15px currentColor; }
    .role-title { font-weight: 700; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
    /* 5. Dashboard Stats (Student/Teacher View) */
    .stat-mini-box { background: rgba(0, 0, 0, 0.3); border-radius: 16px; padding: 20px; border: 1px solid rgba(255, 255, 255, 0.05); display: flex; align-items: center; gap: 15px; transition: 0.3s; }
    .stat-mini-box:hover { background: rgba(255, 255, 255, 0.05); transform: scale(1.02); }
    .stat-mini-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-icon-primary { background: rgba(67, 97, 238, 0.2); color: #4361ee; }
    .bg-icon-success { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
    .bg-icon-warning { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
    .stat-value { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .stat-label { font-size: 0.85rem; color: rgba(255, 255, 255, 0.6); }
    /* Buttons */
    .btn-neon-primary { background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%); color: white; border: none; padding: 12px 30px; border-radius: 50px; font-weight: 700; box-shadow: 0 0 15px rgba(67, 97, 238, 0.4); transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-neon-primary:hover { transform: translateY(-2px); box-shadow: 0 0 25px rgba(67, 97, 238, 0.6); color: white; }
    .btn-neon-outline { background: transparent; border: 2px solid rgba(255, 255, 255, 0.2); color: white; padding: 12px 30px; border-radius: 50px; font-weight: 700; transition: 0.3s; text-decoration: none; display: inline-block; }
    .btn-neon-outline:hover { border-color: #4cc9f0; color: #4cc9f0; box-shadow: 0 0 15px rgba(76, 201, 240, 0.3); }
</style>

<div class="homepage">
    <div class="container">

        <?php if ($isGuest): ?>
            <div class="glass-hero text-center animate__animated animate__fadeIn">
                <div class="hero-content">
                    <h1 class="hero-title animate__animated animate__fadeInDown">
                        <?= Yii::t('app', 'Transform Your Future') ?>
                    </h1>
                    <p class="hero-subtitle animate__animated animate__fadeInUp">
                        <?= Yii::t('app', 'Join thousands of students and expert teachers in our next-gen learning community. Start your journey today.') ?>
                    </p>

                    <div class="row justify-content-center g-4 mb-5 animate__animated animate__zoomIn">
                        <div class="col-md-5 col-lg-4">
                            <a href="<?= Url::to(['/site/teacher-login']) ?>" class="role-card teacher">
                                <i class="fas fa-chalkboard-teacher role-icon"></i>
                                <div class="role-title"><?= Yii::t('app', 'I\'m a Teacher') ?></div>
                                <div class="small text-white-50 mt-2"><?= Yii::t('app', 'Share knowledge & inspire') ?></div>
                            </a>
                        </div>
                        <div class="col-md-5 col-lg-4">
                            <a href="<?= Url::to(['/site/student-portal']) ?>" class="role-card student">
                                <i class="fas fa-user-graduate role-icon"></i>
                                <div class="role-title"><?= Yii::t('app', 'I\'m a Student') ?></div>
                                <div class="small text-white-50 mt-2"><?= Yii::t('app', 'Learn new skills & grow') ?></div>
                            </a>
                        </div>
                    </div>
                    <p class="mt-4 text-white-50">
                        <?= Yii::t('app', 'Already have an account?') ?>
                        <?= Html::a(Yii::t('app', 'Login here'), ['/site/login'], ['class' => 'text-info fw-bold text-decoration-none']) ?>
                    </p>
                </div>
            </div>

        <?php elseif ($role === 'student'): ?>
            <?php
            $student = \common\models\Student::findOne(['user_id' => $user->id]);
            $enrollments = $student ? $student->enrollments : [];
            $payments = $student ? $student->payments : [];
            $completedCount = count(array_filter($enrollments, fn($e) => $e->status === 'completed'));
            ?>
            <div class="glass-hero animate__animated animate__fadeIn">
                <div class="hero-content">
                    <div class="row align-items-center">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <span class="badge bg-primary mb-2">STUDENT PORTAL</span>
                            <h1 class="display-4 fw-bold mb-3 text-white">
                                <?= Yii::t('app', 'Welcome back, {name}!', ['name' => Html::encode(explode(' ', $student->full_name)[0])]) ?> 👋
                            </h1>
                            <p class="lead text-white-50 mb-4">
                                <?= Yii::t('app', 'Ready to continue your learning journey? Your dashboard is ready.') ?>
                            </p>

                            <div class="d-flex gap-3">
                                <?= Html::a('<i class="fas fa-tachometer-alt me-2"></i> ' . Yii::t('app', 'My Dashboard'), ['/student/dashboard'], ['class' => 'btn-neon-primary']) ?>
                                <?= Html::a('<i class="fas fa-book me-2"></i> ' . Yii::t('app', 'Browse Courses'), ['/site/courses'], ['class' => 'btn-neon-outline']) ?>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-primary"><i class="fas fa-book-reader"></i></div>
                                        <div>
                                            <div class="stat-value"><?= count($enrollments) ?></div>
                                            <div class="stat-label"><?= Yii::t('app', 'Enrolled Courses') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-success"><i class="fas fa-check-circle"></i></div>
                                        <div>
                                            <div class="stat-value"><?= $completedCount ?></div>
                                            <div class="stat-label"><?= Yii::t('app', 'Completed') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-warning"><i class="fas fa-wallet"></i></div>
                                        <div>
                                            <div class="stat-value" style="font-size: 1.1rem;">
                                                <?= number_format(array_sum(array_column($payments, 'amount')), 0) ?>
                                            </div>
                                            <div class="stat-label">UZS Paid</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($role === 'teacher'): ?>
            <?php
            $teacher = \common\models\Teacher::findOne(['email' => $user->email]);
            $courses = $teacher ? $teacher->courses : [];
            
            // 🔥 GURUH O'RNIGA TO'G'RIDAN-TO'G'RI KURS ORQALI TALABALARNI SANAYMIZ
            $uniqueStudents = [];
            $totalEnrollments = 0;
            if ($teacher) {
                $enrollments = \common\models\Enrollment::find()
                    ->joinWith('course')
                    ->where(['course.teacher_id' => $teacher->id, 'enrollment.status' => 'active'])
                    ->all();
                $totalEnrollments = count($enrollments);
                foreach ($enrollments as $enrollment) {
                    $uniqueStudents[$enrollment->student_id] = true;
                }
            }
            $totalStudents = count($uniqueStudents);
            ?>
            <div class="glass-hero animate__animated animate__fadeIn">
                <div class="hero-content">
                    <div class="row align-items-center">
                        <div class="col-lg-7 mb-4 mb-lg-0">
                            <span class="badge bg-danger mb-2"><?= Yii::t('app', 'INSTRUCTOR PORTAL') ?></span>
                            <h1 class="display-4 fw-bold mb-2 text-white">
                                <?= Yii::t('app', 'Hello, Professor {name}!', ['name' => Html::encode(explode(' ', $teacher->full_name)[0])]) ?> 👨‍🏫
                            </h1>
                            <p class="text-info fw-bold mb-3 text-uppercase letter-spacing-1">
                    <?= Yii::t('app', '{subject} Specialist', ['subject' => Html::encode($teacher->subject)]) ?>
                            </p>

                            <div class="d-flex align-items-center gap-4 mb-4 text-white-50">
    <span>
        <i class="fas fa-star text-warning me-1"></i> 
        <?= Yii::t('app', '{rating} Rating', ['rating' => $teacher->rating]) ?>
    </span>
    <span>
        <i class="fas fa-briefcase text-success me-1"></i> 
        <?= Yii::t('app', '{years} Years Exp.', ['years' => $teacher->experience_years]) ?>
    </span>
</div>

                            <div class="d-flex gap-3">
                                <?= Html::a('<i class="fas fa-chalkboard me-2"></i> ' . Yii::t('app', 'My Dashboard'), ['/teacher/dashboard'], ['class' => 'btn-neon-primary']) ?>
                                <?= Html::a('<i class="fas fa-users me-2"></i> ' . Yii::t('app', 'My Students'), ['/teacher/my-students'], ['class' => 'btn-neon-outline']) ?>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <h5 class="text-white mb-3 ps-1"><i class="fas fa-chart-pie me-2 text-warning"></i> <?= Yii::t('app', 'Your Impact') ?></h5>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-primary"><i class="fas fa-laptop-code"></i></div>
                                        <div>
                                            <div class="stat-value"><?= count($courses) ?></div>
                                            <div class="stat-label"><?= Yii::t('app', 'Active Courses') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-warning"><i class="fas fa-clipboard-check"></i></div>
                                        <div>
                                            <div class="stat-value"><?= $totalEnrollments ?></div>
                                            <div class="stat-label"><?= Yii::t('app', 'Enrollments') ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="stat-mini-box">
                                        <div class="stat-mini-icon bg-icon-success"><i class="fas fa-user-graduate"></i></div>
                                        <div>
                                            <div class="stat-value"><?= $totalStudents ?></div>
                                            <div class="stat-label"><?= Yii::t('app', 'Unique Students Taught') ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>