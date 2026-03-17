<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\EnrollmentApplication;

$this->title = Yii::t('app', 'Application') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enrollment Applications'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$statusClass = EnrollmentApplication::getStatusBadgeClass($model->status);
?>

<div class="enrollment-application-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1><i class="fas fa-clipboard-list"></i> <?= Html::encode($this->title) ?></h1>
            <span class="badge bg-<?= $statusClass ?> fs-6"><?= Yii::t('app', ucfirst($model->status)) ?></span>
        </div>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php if ($model->status === EnrollmentApplication::STATUS_PENDING): ?>
    <div class="card shadow mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-exclamation-triangle"></i> <?= Yii::t('app', 'Pending Review') ?></h5>
        </div>
        <div class="card-body">
            <p class="mb-3"><?= Yii::t('app', 'Review this enrollment application:') ?></p>
            <div class="d-flex gap-2">
                <?= Html::a('<i class="fas fa-check"></i> ' . Yii::t('app', 'Approve & Enroll'), 
                    ['approve', 'id' => $model->id], 
                    [
                        'class' => 'btn btn-success btn-lg',
                        'data-confirm' => Yii::t('app', 'Approve this application? Student will be enrolled immediately.'),
                    ]) ?>
                
                <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Reject'), 
                    ['reject', 'id' => $model->id], 
                    [
                        'class' => 'btn btn-danger btn-lg',
                        'data-confirm' => Yii::t('app', 'Reject this application?'),
                    ]) ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Application Details') ?></h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'attribute' => 'student_id',
                                'label' => Yii::t('app', 'Student'),
                                'value' => $model->student->full_name . ' (' . $model->student->email . ')',
                            ],
                            [
                                'attribute' => 'course_id',
                                'label' => Yii::t('app', 'Course'),
                                'value' => $model->course->name,
                            ],
                            [
                                'label' => Yii::t('app', 'Course Type'),
                                'value' => strtoupper($model->course->type),
                            ],
                            // 🔥 Group_id qatori butunlay olib tashlandi
                            [
                                'attribute' => 'created_at',
                                'format' => ['date', 'php:Y-m-d H:i'],
                                'label' => Yii::t('app', 'Applied At'),
                            ],
                        ],
                    ]) ?>

                    <?php if ($model->message): ?>
                        <hr>
                        <h6><?= Yii::t('app', 'Student\'s Message:') ?></h6>
                        <p class="bg-light p-3 rounded"><?= nl2br(Html::encode($model->message)) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
                    <h5 class="mb-0"><i class="fas fa-flag"></i> <?= Yii::t('app', 'Status') ?></h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'label' => Yii::t('app', 'Status'),
                                'value' => '<span class="badge bg-' . $statusClass . ' fs-6">' . Yii::t('app', ucfirst($model->status)) . '</span>',
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
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-comment"></i> <?= Yii::t('app', 'Admin Comment') ?></h5>
                </div>
                <div class="card-body">
                    <p><?= nl2br(Html::encode($model->admin_comment)) ?></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>