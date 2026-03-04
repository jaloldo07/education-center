<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Link Tests');
?>

<style>
    /* 1. Page Container */
    .link-test-page {
        padding: 40px 0;
        font-family: 'Nunito', sans-serif;
    }

    /* 2. Header Gradient */
    .glass-header {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .header-title h2 {
        font-weight: 800;
        color: white;
        margin: 0;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(67, 97, 238, 0.5);
    }

    .header-subtitle {
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
        font-size: 1rem;
    }
    .header-subtitle strong { color: var(--accent-color); }

    /* 3. Linked Tests Card */
    .linked-glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }

    .card-header-neon {
        background: linear-gradient(90deg, #4361ee 0%, #3a0ca3 100%);
        padding: 20px 25px;
        color: white;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex; align-items: center; gap: 10px;
    }

    /* Table Styles */
    .table-glass {
        width: 100%;
        color: white;
        margin: 0;
    }

    .table-glass th {
        background: rgba(0,0,0,0.2);
        padding: 15px 20px;
        color: rgba(255,255,255,0.6);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .table-glass td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }
    .table-glass tr:hover td { background: rgba(255,255,255,0.05); }

    .test-name { font-weight: 700; font-size: 1rem; color: white; }
    
    .order-badge {
        width: 35px; height: 35px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }

    /* 4. Link New Test Form (Glass Box) */
    .form-glass-box {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 30px;
        position: relative;
        overflow: hidden;
    }
    /* Neon border effect */
    .form-glass-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background: #4ade80; /* Green accent for adding */
    }

    .form-header {
        margin-bottom: 25px;
        font-size: 1.3rem;
        font-weight: 700;
        color: #4ade80;
        display: flex; align-items: center; gap: 10px;
    }

    /* Inputs */
    .form-glass-input, .form-select-glass {
        background: rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px;
        padding: 12px 15px;
        transition: 0.3s;
    }
    .form-glass-input:focus, .form-select-glass:focus {
        border-color: #4ade80 !important;
        box-shadow: 0 0 0 4px rgba(74, 222, 128, 0.2) !important;
        outline: none;
    }

    /* Buttons */
    .btn-link-neon {
        background: linear-gradient(135deg, #4ade80, #22c55e);
        color: #064e3b;
        border: none;
        padding: 12px 40px;
        border-radius: 12px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 0 20px rgba(74, 222, 128, 0.4);
        transition: 0.3s;
    }
    .btn-link-neon:hover {
        transform: translateY(-3px);
        box-shadow: 0 0 30px rgba(74, 222, 128, 0.6);
        color: #064e3b;
    }

    .btn-remove-glass {
        width: 35px; height: 35px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        background: rgba(248, 113, 113, 0.2);
        color: #f87171;
        transition: 0.3s;
        text-decoration: none;
        border: 1px solid rgba(248, 113, 113, 0.3);
    }
    .btn-remove-glass:hover {
        background: #f87171; color: white;
        box-shadow: 0 0 15px rgba(248, 113, 113, 0.5);
    }

    .btn-glass-back {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
        text-decoration: none;
    }
    .btn-glass-back:hover { background: white; color: black; }

    /* Badges */
    .badge-final {
        background: rgba(244, 114, 182, 0.2);
        color: #f472b6;
        border: 1px solid rgba(244, 114, 182, 0.3);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    
    .badge-regular {
        background: rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.6);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
    }

    /* Empty State */
    .empty-glass {
        text-align: center;
        padding: 50px;
        color: rgba(255,255,255,0.5);
    }

</style>

<div class="link-test-page">
    <div class="container">
        
        <div class="glass-header animate__animated animate__fadeInDown">
            <div class="header-title">
                <h2>🔗 <?= Html::encode($this->title) ?></h2>
                <div class="header-subtitle">
                    <?= Yii::t('app', 'Course') ?>: <strong><?= Html::encode($course->name) ?></strong>
                </div>
            </div>
            <div>
                <?= Html::a('<i class="fas fa-arrow-left me-2"></i> ' . Yii::t('app', 'Back'), ['index'], ['class' => 'btn-glass-back']) ?>
            </div>
        </div>

        <div class="linked-glass-card animate__animated animate__fadeInUp">
            <div class="card-header-neon">
                <i class="fas fa-link"></i> <?= Yii::t('app', 'Currently Linked Tests') ?> 
                <span class="badge bg-white text-primary ms-2"><?= count($linkedTests) ?></span>
            </div>
            
            <?php if (empty($linkedTests)): ?>
                <div class="empty-glass">
                    <i class="fas fa-unlink fa-3x mb-3 opacity-50"></i>
                    <p class="mb-0"><?= Yii::t('app', 'No tests linked to this course yet.') ?></p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table-glass">
                        <thead>
                            <tr>
                                <th><?= Yii::t('app', 'Test Name') ?></th>
                                <th><?= Yii::t('app', 'Order') ?></th>
                                <th><?= Yii::t('app', 'Type') ?></th>
                                <th class="text-end"><?= Yii::t('app', 'Actions') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($linkedTests as $linked): ?>
                                <tr>
                                    <td>
                                        <div class="test-name">📝 <?= Html::encode($linked->test->title) ?></div>
                                    </td>
                                    <td>
                                        <div class="order-badge"><?= $linked->order_number ?></div>
                                    </td>
                                    <td>
                                        <?php if ($linked->is_final_test): ?>
                                            <span class="badge-final">🏆 <?= Yii::t('app', 'Final') ?></span>
                                        <?php else: ?>
                                            <span class="badge-regular"><?= Yii::t('app', 'Regular') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <?= Html::a('<i class="fas fa-trash-alt"></i>', ['unlink-test', 'id' => $linked->id], [
                                            'class' => 'btn-remove-glass',
                                            'data' => ['confirm' => Yii::t('app', 'Remove this test link?'), 'method' => 'post'],
                                            'title' => Yii::t('app', 'Unlink')
                                        ]) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="form-glass-box animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
            <div class="form-header">
                <i class="fas fa-plus-circle"></i> <?= Yii::t('app', 'Link New Test') ?>
            </div>

            <?php if (empty($tests)): ?>
                <div class="alert alert-warning bg-opacity-25 border border-warning text-white d-flex align-items-center gap-3">
                    <i class="fas fa-exclamation-triangle fs-4 text-warning"></i>
                    <div>
                        <strong><?= Yii::t('app', 'No tests available!') ?></strong><br>
                        <?= Yii::t('app', 'You need to create tests first before linking them.') ?>
                        <div class="mt-2">
                            <?= Html::a(Yii::t('app', 'Create Test'), ['/test/create'], ['class' => 'btn btn-sm btn-outline-warning']) ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                
                <?php $form = ActiveForm::begin(); ?>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="text-white-50 fw-bold mb-2"><?= Yii::t('app', 'Select Test') ?></label>
                        <?= $form->field($model, 'test_id')->dropDownList(
                            ArrayHelper::map($tests, 'id', 'title'),
                            [
                                // 🔥 TUZATILDI: Prompt tarjima qilindi
                                'prompt' => Yii::t('app', 'Select a test...'), 
                                'class' => 'form-select form-select-glass'
                            ]
                        )->label(false) ?>
                    </div>
                </div>

                <div class="row align-items-end mb-4">
                    <div class="col-md-6">
                        <label class="text-white-50 fw-bold mb-2"><?= Yii::t('app', 'Display Order') ?></label>
                        <?= $form->field($model, 'order_number')->textInput([
                            'type' => 'number',
                            'value' => count($linkedTests) + 1,
                            'min' => 1,
                            'class' => 'form-control form-glass-input'
                        ])->label(false) ?>
                        <div class="small text-white-50 mt-1"><?= Yii::t('app', 'Order in which tests appear to students') ?></div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-check form-switch p-3 rounded bg-white bg-opacity-10 border border-white border-opacity-10">
                            <?= $form->field($model, 'is_final_test')->checkbox([
                                'class' => 'form-check-input',
                                'labelOptions' => ['class' => 'form-check-label text-white fw-bold']
                            ])->label('🏆 ' . Yii::t('app', 'Mark as Final Test')) ?>
                            <div class="small text-white-50 ms-4 mt-1"><?= Yii::t('app', 'This is the main course assessment') ?></div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <?= Html::submitButton('<i class="fas fa-link me-2"></i> ' . Yii::t('app', 'Link Test'), ['class' => 'btn-link-neon']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            <?php endif; ?>
        </div>

    </div>
</div>