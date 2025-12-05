<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\TeacherApplication;

$this->title = 'Application: ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Teacher Applications', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$statusClass = TeacherApplication::getStatusBadgeClass($model->status);
$statusText = TeacherApplication::getStatusOptions()[$model->status];
?>

<div class="teacher-application-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-file-alt"></i> <?= Html::encode($model->full_name) ?>'s Application</h1>
            <span class="badge bg-<?= $statusClass ?> fs-6"><?= $statusText ?></span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <!-- Action Buttons -->
    <?php if ($model->status === TeacherApplication::STATUS_PENDING): ?>
        <div class="card shadow mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Pending Review</h5>
            </div>
            <div class="card-body">
                <p class="mb-3">Review this application and take action:</p>
                <div class="d-flex gap-2">
                    <?= Html::a(
                        '<i class="fas fa-check"></i> Approve & Create Teacher',
                        ['approve', 'id' => $model->id],
                        [
                            'class' => 'btn btn-success btn-lg',
                            'data-confirm' => 'Are you sure you want to approve this application? This will create a teacher account.',
                        ]
                    ) ?>

                    <?= Html::a(
                        '<i class="fas fa-times"></i> Reject',
                        ['reject', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger btn-lg',
                            'data-confirm' => 'Are you sure you want to reject this application?',
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Application Details -->
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Personal Information</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'full_name',
                            'email:email',
                            'phone',
                            'subject',
                            'experience_years',
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Professional Background</h5>
                </div>
                <div class="card-body">
                    <h6>Education:</h6>
                    <p><?= nl2br(Html::encode($model->education)) ?></p>

                    <hr>

                    <h6>Biography:</h6>
                    <p><?= nl2br(Html::encode($model->bio)) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- CV Download -->
            <?php if ($model->cv_file): ?>
                <div class="card shadow mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                        <h5 class="mb-0"><i class="fas fa-file-pdf"></i> Curriculum Vitae</h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <p class="text-muted small"><?= Html::encode($model->cv_file) ?></p>
                        <?= Html::a(
                            '<i class="fas fa-download"></i> Download CV',
                            $model->getCvUrl(),
                            ['class' => 'btn btn-success w-100', 'target' => '_blank']
                        ) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Application Status -->
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Application Status</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => '<span class="badge bg-' . $statusClass . '">' . $statusText . '</span>',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:Y-m-d H:i'],
                                'label' => 'Applied At',
                            ],
                            [
                                'attribute' => 'reviewed_at',
                                'format' => ['date', 'php:Y-m-d H:i'],
                                'label' => 'Reviewed At',
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- Admin Comment / Credentials -->
            <?php if ($model->admin_comment): ?>
                <div class="card shadow">
                    <div class="card-header <?= $model->status === 'approved' ? 'bg-success' : 'bg-warning' ?> text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-<?= $model->status === 'approved' ? 'key' : 'comment' ?>"></i>
                            <?= $model->status === 'approved' ? 'Login Credentials' : 'Admin Comment' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <pre style="white-space: pre-wrap; font-size: 14px;"><?= Html::encode($model->admin_comment) ?></pre>

                        <?php if ($model->status === 'approved'): ?>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-envelope"></i> <strong>Remember:</strong> Send these credentials to the teacher via email!
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>