<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Welcome to Education Center';

// Current user check
$isGuest = Yii::$app->user->isGuest;
$user = !$isGuest ? Yii::$app->user->identity : null;
$role = $user ? $user->role : null;
?>

<div class="homepage">

    <?php if ($isGuest): ?>
        <!-- 🎯 GUEST HERO SECTION -->
        <section class="hero-section text-white text-center py-5 animate__animated animate__fadeIn" style="background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;">
            <div class="container">
                <h1 class="display-3 fw-bold mb-4 animate__animated animate__bounceInDown">
                    Transform Your Future with Education
                </h1>
                <p class="lead mb-5 animate__animated animate__fadeInUp">
                    Join thousands of students and expert teachers in our learning community
                </p>

                <div class="d-flex justify-content-center gap-3 mb-4 animate__animated animate__zoomIn">
                    <?= Html::a(
                        '<i class="fas fa-chalkboard-teacher fa-2x mb-3"></i><br>I\'m a Teacher',
                        ['/site/teacher-login'],
                        ['class' => 'btn btn-light btn-lg px-5 py-4 role-btn']
                    ) ?>

                    <?= Html::a(
                        '<i class="fas fa-user-graduate fa-2x mb-3"></i><br>I\'m a Student',
                        ['/site/student-portal'],
                        ['class' => 'btn btn-warning btn-lg px-5 py-4 role-btn']
                    ) ?>
                </div>

                <p class="mt-4">
                    <small>Already have an account? <?= Html::a('Login here', ['/site/login'], ['class' => 'text-white fw-bold']) ?></small>
                </p>
            </div>
        </section>

    <?php elseif ($role === 'student'): ?>
        <!-- 🎓 STUDENT HERO SECTION -->
        <?php
        $student = \common\models\Student::findOne(['user_id' => $user->id]);
        $enrollments = $student ? $student->enrollments : [];
        $payments = $student ? $student->payments : [];
        ?>
        <section class="hero-section text-white py-5" 
        style="background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;"
        >
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInLeft">
                            Welcome back, <?= Html::encode(explode(' ', $student->full_name)[0]) ?>! 🎓
                        </h1>
                        <p class="lead mb-4">Continue your learning journey</p>

                        <div class="d-flex gap-3">
                            <?= Html::a(
                                '<i class="fas fa-tachometer-alt"></i> My Dashboard',
                                ['/student/dashboard'],
                                ['class' => 'btn btn-light btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-book"></i> Browse Courses',
                                ['/site/courses'],
                                ['class' => 'btn btn-outline-light btn-lg']
                            ) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-white bg-opacity-25 text-white border-0 animate__animated animate__fadeInRight">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="fas fa-chart-line"></i> Your Progress</h5>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Enrolled Courses</span>
                                        <strong><?= count($enrollments) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Completed</span>
                                        <strong><?= count(array_filter($enrollments, fn($e) => $e->status === 'completed')) ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Total Paid</span>
                                        <strong><?= number_format(array_sum(array_column($payments, 'amount')), 0) ?> UZS</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php elseif ($role === 'teacher'): ?>
        <!-- 👨‍🏫 TEACHER HERO SECTION -->
        <?php
        $teacher = \common\models\Teacher::findOne(['email' => $user->email]);
        $groups = $teacher ? $teacher->groups : [];
        $courses = $teacher ? $teacher->courses : [];
        $totalStudents = 0;
        foreach ($groups as $group) {
            $totalStudents += count($group->students);
        }
        ?>
        <section class="hero-section text-white py-5" 
        style="background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease; min-height: 400px;"
        >
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold mb-3 animate__animated animate__fadeInLeft">
                            Welcome, Professor <?= Html::encode(explode(' ', $teacher->full_name)[0]) ?>! 👨‍🏫
                        </h1>
                        <p class="lead mb-2"><?= Html::encode($teacher->subject) ?> Specialist</p>
                        <p class="mb-4">
                            <i class="fas fa-star text-warning"></i> <?= $teacher->rating ?> Rating •
                            <i class="fas fa-award"></i> <?= $teacher->experience_years ?> Years Experience
                        </p>

                        <div class="d-flex gap-3">
                            <?= Html::a(
                                '<i class="fas fa-chalkboard"></i> My Dashboard',
                                ['/teacher/dashboard'],
                                ['class' => 'btn btn-light btn-lg']
                            ) ?>
                            <?= Html::a(
                                '<i class="fas fa-users"></i> My Groups',
                                ['/teacher/dashboard'],
                                ['class' => 'btn btn-outline-light btn-lg']
                            ) ?>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card bg-white bg-opacity-25 text-white border-0 animate__animated animate__fadeInRight">
                            <div class="card-body">
                                <h5 class="mb-3"><i class="fas fa-chart-bar"></i> Your Impact</h5>
                                <div class="row text-center g-3">
                                    <div class="col-6">
                                        <div class="bg-white bg-opacity-25 rounded p-3">
                                            <h3 class="mb-0"><?= count($courses) ?></h3>
                                            <small>Courses</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white bg-opacity-25 rounded p-3">
                                            <h3 class="mb-0"><?= count($groups) ?></h3>
                                            <small>Groups</small>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="bg-white bg-opacity-25 rounded p-3">
                                            <h3 class="mb-0"><?= $totalStudents ?></h3>
                                            <small>Total Students Taught</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Qolgan qismlar o'zgarmaydi (Statistics, Courses, Teachers, CTA) -->