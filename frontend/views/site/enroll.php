<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = Yii::t('app', 'Enroll in') . ' ' . $course->name;
$isFree = $course->isFree();
?>

<style>
    .enroll-page {
        /* Orqa fon shaffof, chunki Main Layoutdagi Canvas ko'rinadi */
        background: transparent;
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Asosiy Glass Card */
    .enroll-card {
        background: rgba(15, 23, 42, 0.75); /* To'q fon */
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        overflow: hidden;
        color: white;
        transition: transform 0.3s;
    }
    
    .enroll-card:hover {
        border-color: rgba(255, 255, 255, 0.2);
    }

    /* Header */
    .enroll-header {
        background: linear-gradient(90deg, #4361ee, #4cc9f0);
        padding: 30px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .enroll-title {
        font-weight: 800;
        margin: 0;
        font-size: 1.5rem;
        letter-spacing: 1px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Ticket Info Section */
    .course-ticket {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 25px;
        border: 1px dashed rgba(255, 255, 255, 0.2);
    }

    /* Dark Input Fields */
    .form-control-dark, .form-select-dark {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 12px;
        padding: 12px 15px;
        transition: 0.3s;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: var(--accent-color);
        color: white;
        box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.2);
    }
    
    /* Dropdown icon fix for dark mode */
    .form-select-dark {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
    }

    .form-label {
        font-weight: 600;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    /* Buttons */
    .btn-glow {
        background: linear-gradient(90deg, #4361ee, #3f37c9);
        border: none;
        padding: 14px;
        border-radius: 12px;
        font-weight: 700;
        width: 100%;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: 0.3s;
        box-shadow: 0 0 15px rgba(67, 97, 238, 0.4);
    }

    .btn-glow:hover {
        box-shadow: 0 0 25px rgba(67, 97, 238, 0.6);
        transform: translateY(-2px);
        color: white;
    }

    .btn-cancel {
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 15px;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .btn-cancel:hover { color: white; }

</style>

<div class="enroll-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                
                <div class="enroll-card animate__animated animate__fadeInUp">
                    <div class="enroll-header">
                        <i class="fas fa-rocket fa-2x mb-2 opacity-75"></i>
                        <h1 class="enroll-title"><?= Yii::t('app', 'Enrollment Application') ?></h1>
                    </div>

                    <div class="card-body p-4">

                        <div class="course-ticket">
                            <h4 class="fw-bold mb-1 text-info">
                                <?= Html::encode($course->name) ?>
                            </h4>
                            <p class="text-white-50 small mb-3">
                                <i class="fas fa-chalkboard-teacher mr-1"></i> <?= Html::encode($course->teacher->full_name) ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-end border-top pt-3" style="border-color: rgba(255,255,255,0.1) !important;">
                                <div>
                                    <small class="text-white-50 d-block text-uppercase fw-bold" style="font-size: 0.65rem;">
                                        <?= Yii::t('app', 'TOTAL PRICE') ?>
                                    </small>
                                    <div style="font-size: 1.4rem; font-weight: 800; color: white;">
                                        <?= number_format($course->price, 0) ?> 
                                        <small class="fs-6 text-white-50">UZS</small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge <?= $isFree ? 'bg-success' : 'bg-warning text-dark' ?> px-3 py-2">
                                        <?= $isFree ? 'FREE' : 'PREMIUM' ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <?php if ($isFree): ?>
                            <div class="alert alert-success d-flex align-items-center p-2 mb-4 bg-success bg-opacity-25 border-success text-white">
                                <i class="fas fa-check-circle fs-4 me-3"></i>
                                <div class="small">
                                    <strong><?= Yii::t('app', 'Instant Access!') ?></strong><br>
                                    <?= Yii::t('app', 'You will be enrolled immediately.') ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'inputOptions' => ['class' => 'form-control-dark'],
                                'labelOptions' => ['class' => 'form-label'],
                            ]
                        ]); ?>

                        <div class="mb-3">
                            <?= $form->field($model, 'group_id')->dropDownList(
                                ArrayHelper::map($groups, 'id', function($group) {
                                    return $group->name . ' (' . count($group->students) . ' students)';
                                }),
                                ['prompt' => Yii::t('app', 'Select a Group...'), 'class' => 'form-select form-select-dark']
                            )->label(Yii::t('app', 'Choose Group')) ?>
                        </div>

                        <?php if (!$isFree): ?>
                            <div class="mb-3">
                                <?= $form->field($model, 'message')->textarea([
                                    'rows' => 3,
                                    'placeholder' => Yii::t('app', 'Optional note...'),
                                    'class' => 'form-control-dark'
                                ])->label(Yii::t('app', 'Message (Optional)')) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-4">
                            <?= Html::submitButton(
                                $isFree ? Yii::t('app', 'Confirm Enrollment') : Yii::t('app', 'Submit Application'),
                                ['class' => 'btn-glow']
                            ) ?>
                            
                            <?= Html::a(Yii::t('app', 'Cancel'), 
                                ['course-detail', 'id' => $course->id], 
                                ['class' => 'btn-cancel']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>