<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Enroll in ' . $course->name;

$isFree = $course->isFree();
?>

<div class="enroll-page py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-<?= $isFree ? 'success' : 'warning' ?> text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-graduation-cap"></i> <?= Html::encode($this->title) ?>
                        </h4>
                    </div>
                    <div class="card-body p-4">

                        <!-- Course Info -->
                        <div class="alert alert-<?= $isFree ? 'success' : 'warning' ?>">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1"><?= Html::encode($course->name) ?></h5>
                                    <p class="mb-0">
                                        <i class="fas fa-user-tie"></i> <?= Html::encode($course->teacher->full_name) ?> | 
                                        <i class="fas fa-clock"></i> <?= $course->duration ?> months
                                    </p>
                                </div>
                                <div class="text-end">
                                    <h4 class="mb-0"><?= number_format($course->price, 0) ?> UZS</h4>
                                    <span class="badge bg-<?= $isFree ? 'success' : 'warning' ?>">
                                        <?= $isFree ? 'FREE' : 'PREMIUM' ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if ($isFree): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> <strong>FREE COURSE:</strong> You will be enrolled immediately after selecting a group!
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-shield-check"></i> <strong>PREMIUM COURSE:</strong> Your application will be reviewed by our admin team. You'll receive a notification once approved.
                            </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <?php $form = ActiveForm::begin(); ?>

                        <?= $form->field($model, 'group_id')->dropDownList(
                            ArrayHelper::map($groups, 'id', function($group) {
                                return $group->name . ' (' . count($group->students) . ' students)';
                            }),
                            ['prompt' => 'Select a Group', 'class' => 'form-select form-select-lg']
                        )->label('Choose Your Group') ?>

                        <?php if (!$isFree): ?>
                            <?= $form->field($model, 'message')->textarea([
                                'rows' => 4,
                                'placeholder' => 'Why do you want to join this course? (Optional)',
                                'class' => 'form-control'
                            ])->label('Message to Admin (Optional)') ?>
                        <?php endif; ?>

                        <div class="d-grid gap-2 mt-4">
                            <?= Html::submitButton(
                                $isFree ? '<i class="fas fa-check-circle"></i> Enroll Now (FREE)' : '<i class="fas fa-paper-plane"></i> Submit Application',
                                ['class' => 'btn btn-' . ($isFree ? 'success' : 'warning') . ' btn-lg']
                            ) ?>
                            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to Course', 
                                ['course-detail', 'id' => $course->id], 
                                ['class' => 'btn btn-outline-secondary btn-lg']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>