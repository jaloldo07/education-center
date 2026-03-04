
<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Student Portal');

?>


<style>
    body {
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* 🔹 Login Card */
    .card {
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.25);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.25);
    }

    .student-portal-page .card {
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .student-portal-page .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3) !important;
    }

    .student-portal-page .btn {
        transition: all 0.3s;
    }

    .student-portal-page .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
</style>


<div class="student-portal-page">
    <div class="container py-5">
        <div class="text-center text-white mb-5 animate__animated animate__fadeInDown">
            <div class="d-inline-flex align-items-center justify-content-center bg-white text-primary rounded-circle mb-4" style="width: 100px; height: 100px;">
                <i class="fas fa-user-graduate fa-4x"></i>
            </div>
            <h1 class="display-4 fw-bold mb-3"><?= Yii::t('app', 'Welcome, Student!') ?></h1>
            <p class="lead"><?= Yii::t('app', 'Choose an option to continue your learning journey') ?></p>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-lg-5">
                <div class="card h-100 shadow-lg border-0 animate__animated animate__fadeInLeft">
                    <div class="card-body p-5 text-center">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-sign-in-alt fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-3"><?= Yii::t('app', 'Already Registered?') ?></h3>
                        <p class="text-muted mb-4"><?= Yii::t('app', 'Sign in to access your courses, track progress, and manage your learning.') ?></p>

                        <ul class="text-start text-muted mb-4">
                            <li class="mb-2"><i class="fas fa-check text-success"></i> <?= Yii::t('app', 'View enrolled courses') ?></li>
                            <li class="mb-2"><i class="fas fa-check text-success"></i> <?= Yii::t('app', 'Track your progress') ?></li>
                            <li class="mb-2"><i class="fas fa-check text-success"></i> <?= Yii::t('app', 'Payment history') ?></li>
                            <li class="mb-2"><i class="fas fa-check text-success"></i> <?= Yii::t('app', 'Download certificates') ?></li>
                        </ul>

                        <?= Html::a(
                            '<i class="fas fa-sign-in-alt"></i> ' . Yii::t('app', 'Login to Dashboard'),
                            ['site/login'],
                            ['class' => 'btn btn-primary btn-lg w-100']
                        ) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card h-100 shadow-lg border-0 animate__animated animate__fadeInRight">
                    <div class="card-body p-5 text-center">
                        <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-plus fa-3x"></i>
                        </div>
                        <h3 class="fw-bold mb-3"><?= Yii::t('app', 'New Here?') ?></h3>
                        <p class="text-muted mb-4"><?= Yii::t('app', 'Create your account and start your educational journey with us today!') ?></p>

                        <ul class="text-start text-muted mb-4">
                            <li class="mb-2"><i class="fas fa-star text-warning"></i> <?= Yii::t('app', 'Access to 50+ courses') ?></li>
                            <li class="mb-2"><i class="fas fa-star text-warning"></i> <?= Yii::t('app', 'Learn from expert teachers') ?></li>
                            <li class="mb-2"><i class="fas fa-star text-warning"></i> <?= Yii::t('app', 'Flexible schedule') ?></li>
                            <li class="mb-2"><i class="fas fa-star text-warning"></i> <?= Yii::t('app', 'Recognized certificates') ?></li>
                        </ul>

                        <?= Html::a(
                            '<i class="fas fa-user-plus"></i> ' . Yii::t('app', 'Register Now'),
                            ['site/signup'],
                            ['class' => 'btn btn-success btn-lg w-100']
                        ) ?>

                        <p class="text-muted small mt-3 mb-0">
                            <i class="fas fa-shield-alt"></i> <?= Yii::t('app', 'Your data is safe with us') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to Home'), ['/'], ['class' => 'btn btn-outline-light btn-lg']) ?>
        </div>
    </div>
</div>