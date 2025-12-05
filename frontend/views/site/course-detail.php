<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Course;

$this->title = $course->name;

$isFree = $course->isFree();
$isStudent = !Yii::$app->user->isGuest && Yii::$app->user->identity->role === 'student';
?>

<div class="course-detail-page py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                        <h3 class="mb-0"><?= Html::encode($course->name) ?></h3>
                    </div>
                    <div class="card-body" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-<?= $isFree ? 'success' : 'warning' ?> fs-6">
                                <i class="fas fa-<?= $isFree ? 'check-circle' : 'star' ?>"></i>
                                <?= $isFree ? 'FREE COURSE' : 'PREMIUM COURSE' ?>
                            </span>
                            <h4 class="text-primary mb-0"><?= number_format($course->price, 0) ?> UZS/month</h4>
                        </div>

                        <hr>

                        <h5><i class="fas fa-info-circle"></i> About This Course</h5>
                        <p class="lead"><?= nl2br(Html::encode($course->description)) ?></p>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-clock text-info"></i> Duration</h6>
                                <p><?= $course->duration ?> months</p>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-chalkboard-teacher text-success"></i> Instructor</h6>
                                <p><?= Html::encode($course->teacher->full_name) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teacher Info -->
                <div class="card shadow" style="background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-user-tie"></i> About Instructor</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <?= strtoupper(substr($course->teacher->full_name, 0, 1)) ?>
                            </div>
                            <div>
                                <h5 class="mb-0"><?= Html::encode($course->teacher->full_name) ?></h5>
                                <p class="text-muted mb-0"><?= Html::encode($course->teacher->subject) ?></p>
                            </div>
                        </div>
                        <p><?= nl2br(Html::encode($course->teacher->bio)) ?></p>
                        <div class="d-flex gap-3">
                            <span><i class="fas fa-star text-warning"></i> <?= $course->teacher->rating ?> Rating</span>
                            <span><i class="fas fa-award text-info"></i> <?= $course->teacher->experience_years ?> Years Experience</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Enroll Card -->
                <div class="card shadow sticky-top" style="top: 20px; background: linear-gradient(135deg, #7c8ac5ff 0%, #9d75c5ff 100%);">
                    <div class="card-body text-center p-4">
                        <h4 class="mb-3">Ready to Start Learning?</h4>
                        
                        <?php if ($isFree): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-gift"></i> <strong>FREE!</strong> Instant enrollment
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-shield-check"></i> Approval required
                            </div>
                        <?php endif; ?>

                        <?php if (Yii::$app->user->isGuest): ?>
                            <p class="text-muted">Please login or register to enroll</p>
                            <?= Html::a('<i class="fas fa-sign-in-alt"></i> Login', ['/site/login'], ['class' => 'btn btn-primary btn-lg w-100 mb-2']) ?>
                            <?= Html::a('<i class="fas fa-user-plus"></i> Register', ['/site/signup'], ['class' => 'btn btn-outline-primary btn-lg w-100']) ?>
                        
                        <?php elseif ($isStudent): ?>
                            <?= Html::a('<i class="fas fa-check-circle"></i> Enroll Now', 
                                ['site/enroll', 'id' => $course->id], 
                                ['class' => 'btn btn-success btn-lg w-100']) ?>
                        
                        <?php else: ?>
                            <p class="text-muted">Only students can enroll</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>