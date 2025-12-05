<?php
use yii\helpers\Html;

$this->title = $teacher->full_name;
?>

<div class="teacher-detail-page py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card shadow sticky-top" style="top: 20px;">
                    <div class="card-body text-center p-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px; font-size: 3rem;">
                            <?= strtoupper(substr($teacher->full_name, 0, 1)) ?>
                        </div>
                        <h3 class="mb-2"><?= Html::encode($teacher->full_name) ?></h3>
                        <p class="text-muted mb-3"><?= Html::encode($teacher->subject) ?></p>
                        
                        <div class="mb-3">
                            <?php for($i = 0; $i < floor($teacher->rating); $i++): ?>
                                <i class="fas fa-star text-warning"></i>
                            <?php endfor; ?>
                            <span class="ms-2"><strong><?= $teacher->rating ?></strong> Rating</span>
                        </div>

                        <hr>

                        <div class="text-start">
                            <p class="mb-2"><i class="fas fa-award text-info"></i> <strong><?= $teacher->experience_years ?></strong> years experience</p>
                            <p class="mb-2"><i class="fas fa-envelope text-primary"></i> <?= Html::encode($teacher->email) ?></p>
                            <p class="mb-0"><i class="fas fa-phone text-success"></i> <?= Html::encode($teacher->phone) ?></p>
                        </div>

                        <hr>

                        <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Teachers', ['/site/teachers'], ['class' => 'btn btn-secondary w-100']) ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                        <h4 class="mb-0"><i class="fas fa-user-tie"></i> About</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead"><?= nl2br(Html::encode($teacher->bio)) ?></p>
                    </div>
                </div>

                <!-- Courses -->
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-book"></i> Courses by <?= Html::encode(explode(' ', $teacher->full_name)[0]) ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($courses)): ?>
                            <p class="text-muted">No courses available yet.</p>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($courses as $course): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="badge bg-<?= $course->isFree() ? 'success' : 'warning' ?>">
                                                    <?= strtoupper($course->type) ?>
                                                </span>
                                                <strong class="text-primary"><?= number_format($course->price, 0) ?> UZS</strong>
                                            </div>
                                            <h6 class="card-title"><?= Html::encode($course->name) ?></h6>
                                            <p class="card-text small text-muted"><?= Html::encode($course->description) ?></p>
                                            <hr>
                                            <p class="mb-0">
                                                <small><i class="fas fa-clock"></i> <?= $course->duration ?> months</small>
                                            </p>
                                        </div>
                                        <div class="card-footer bg-transparent">
                                            <?= Html::a('View Details', ['course-detail', 'id' => $course->id], ['class' => 'btn btn-sm btn-primary w-100']) ?>
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
</div>