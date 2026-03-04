<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\TeacherApplication;

$this->title = Yii::t('app', 'Application') . ': ' . $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teacher Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$statusClass = TeacherApplication::getStatusBadgeClass($model->status);
$statusText = TeacherApplication::getStatusOptions()[$model->status];
?>

<div class="teacher-application-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-file-alt"></i> <?= Html::encode($model->full_name) ?>'s <?= Yii::t('app', 'Application') ?></h1>
            <span class="badge bg-<?= $statusClass ?> fs-6"><?= Yii::t('app', $statusText) ?></span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php if ($model->status === TeacherApplication::STATUS_PENDING): ?>
        <div class="card shadow mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> <?= Yii::t('app', 'Pending Review') ?></h5>
            </div>
            <div class="card-body">
                <p class="mb-3"><?= Yii::t('app', 'Review this application and take action:') ?></p>
                <div class="d-flex gap-2">
                    <?= Html::a(
                        '<i class="fas fa-check"></i> ' . Yii::t('app', 'Approve & Create Teacher'),
                        ['approve', 'id' => $model->id],
                        [
                            'class' => 'btn btn-success btn-lg',
                            'data-confirm' => Yii::t('app', 'Are you sure you want to approve this application? This will create a teacher account.'),
                        ]
                    ) ?>

                    <?= Html::a(
                        '<i class="fas fa-times"></i> ' . Yii::t('app', 'Reject'),
                        ['reject', 'id' => $model->id],
                        [
                            'class' => 'btn btn-danger btn-lg',
                            'data-confirm' => Yii::t('app', 'Are you sure you want to reject this application?'),
                        ]
                    ) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                    <h5 class="mb-0"><i class="fas fa-user"></i> <?= Yii::t('app', 'Personal Information') ?></h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'full_name',
                                'label' => Yii::t('app', 'Full Name'),
                            ],
                            'email:email',
                            [
                                'attribute' => 'phone',
                                'label' => Yii::t('app', 'Phone'),
                            ],
                            [
                                'attribute' => 'subject',
                                'label' => Yii::t('app', 'Subject'),
                            ],
                            [
                                'attribute' => 'experience_years',
                                'label' => Yii::t('app', 'Experience (Years)'),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> <?= Yii::t('app', 'Professional Background') ?></h5>
                </div>
                <div class="card-body">
                    <h6><?= Yii::t('app', 'Education:') ?></h6>
                    <p><?= nl2br(Html::encode($model->education)) ?></p>

                    <hr>

                    <h6><?= Yii::t('app', 'Biography:') ?></h6>
                    <p><?= nl2br(Html::encode($model->bio)) ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <?php if ($model->cv_file): ?>
                <div class="card shadow mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                        <h5 class="mb-0"><i class="fas fa-file-pdf"></i> <?= Yii::t('app', 'Curriculum Vitae') ?></h5>
                    </div>
                    <div class="card-body text-center">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <p class="text-muted small"><?= Html::encode($model->cv_file) ?></p>
                        
                        <?php 
                            $frontendDomain = 'http://education-center.local';
                            $downloadUrl = $frontendDomain . '/uploads/cv/' . $model->cv_file;
                        ?>
                        
                        <?= Html::a(
                            '<i class="fas fa-download"></i> ' . Yii::t('app', 'Download CV'),
                            $downloadUrl, 
                            ['class' => 'btn btn-success w-100', 'target' => '_blank']
                        ) ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Application Status') ?></h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => Yii::t('app', 'Status'),
                                'value' => '<span class="badge bg-' . $statusClass . '">' . Yii::t('app', $statusText) . '</span>',
                            ],
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:Y-m-d H:i'],
                                'label' => Yii::t('app', 'Applied At'),
                            ],
                            [
                                'attribute' => 'reviewed_at',
                                'format' => ['date', 'php:Y-m-d H:i'],
                                'label' => Yii::t('app', 'Reviewed At'),
                            ],
                        ],
                    ]) ?>
                </div>
            </div>

            <?php if ($model->admin_comment): ?>
                <div class="card shadow">
                    <div class="card-header <?= $model->status === 'approved' ? 'bg-success' : 'bg-warning' ?> text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-<?= $model->status === 'approved' ? 'key' : 'comment' ?>"></i>
                            <?= $model->status === 'approved' ? Yii::t('app', 'Login Credentials') : Yii::t('app', 'Admin Comment') ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <pre style="white-space: pre-wrap; font-size: 14px;"><?= Html::encode($model->admin_comment) ?></pre>

                        <?php if ($model->status === 'approved'): ?>
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-envelope"></i> <strong><?= Yii::t('app', 'Remember:') ?></strong> <?= Yii::t('app', 'Send these credentials to the teacher via email!') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>