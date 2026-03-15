<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $courses array */
/* @var $selectedCourse int|null */

$this->title = Yii::t('app', 'Payments');
$this->params['breadcrumbs'][] = $this->title;

// Calculate totals based on current page data (or entire query if needed, but here simple loop)
$totalAmount = 0;
foreach ($dataProvider->models as $payment) {
    $totalAmount += $payment->amount;
}
// Note: For accurate total of ALL pages, you should use a separate query or aggregation.
// But for simple view, this sums up current page. To sum ALL, use:
// $totalAmount = $dataProvider->query->sum('amount'); 
// However, executing sum query every time might be heavy if table is huge. 
// Let's stick to current page sum or pass total from controller if needed. 
// For now, I will use the loop over current page models as you had.
?>

<style>
    .payment-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .payment-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>


<div class="payment-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-money-bill-wave"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::button('<i class="fas fa-credit-card"></i> Karta sozlamalari', [
                'class' => 'btn btn-warning text-dark fw-bold me-2',
                'data-bs-toggle' => 'modal',
                'data-bs-target' => '#cardSettingsModal'
            ]) ?>
            <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Record Payment'), ['create'], ['class' => 'btn btn-success btn-hover']) ?>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h5 class="mb-0"><i class="fas fa-filter"></i> <?= Yii::t('app', 'Filter by Course') ?></h5>
        </div>
        <div class="card-body">
            <form method="get" action="<?= Url::to(['index']) ?>" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label"><?= Yii::t('app', 'Select Course') ?>:</label>
                    <?= Html::dropDownList('course_id', $selectedCourse, $courses, [
                        'class' => 'form-control form-control-lg',
                        'prompt' => '-- ' . Yii::t('app', 'All Courses') . ' --',
                        'id' => 'course-filter'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> <?= Yii::t('app', 'Filter') ?>
                    </button>
                    <?php if ($selectedCourse): ?>
                        <?= Html::a('<i class="fas fa-times"></i> ' . Yii::t('app', 'Clear'), ['index'], ['class' => 'btn btn-secondary btn-lg w-100 mt-2']) ?>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if ($selectedCourse && isset($courses[$selectedCourse])): ?>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle"></i> <?= Yii::t('app', 'Showing payments for:') ?> 
                    <strong><?= Html::encode($courses[$selectedCourse]) ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($dataProvider->totalCount > 0): ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow bg-success text-white">
                <div class="card-body">
                    <h6 class="text-uppercase"><?= Yii::t('app', 'Total Payments') ?></h6>
                    <h3><?= $dataProvider->totalCount ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-uppercase"><?= Yii::t('app', 'Total Amount (Page)') ?></h6>
                    <h3><?= number_format($totalAmount, 0, '.', ' ') ?> <small><?= Yii::t('app', 'UZS') ?></small></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow bg-info text-white">
                <div class="card-body">
                    <h6 class="text-uppercase"><?= Yii::t('app', 'Average Payment') ?></h6>
                    <?php 
                        // To get real average we should use total sum of query, not page sum. 
                        // But for simplicity let's approximate or just show page average.
                        $avg = $dataProvider->count > 0 ? $totalAmount / $dataProvider->count : 0;
                    ?>
                    <h3><?= number_format($avg, 0, '.', ' ') ?> <small><?= Yii::t('app', 'UZS') ?></small></h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'summary' => Yii::t('app', 'Showing {begin}-{end} of {totalCount} items.'),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'student_id',
                        'value' => 'student.full_name',
                        'label' => Yii::t('app', 'Student'),
                    ],
                    [
                        'attribute' => 'course_id',
                        'value' => 'course.name',
                        'label' => Yii::t('app', 'Course'),
                    ],
                    [
                        'attribute' => 'amount',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Amount'),
                        'value' => function($model) {
                            return '<strong class="text-success">' . number_format($model->amount, 0, '.', ' ') . ' <small>' . Yii::t('app', 'UZS') . '</small></strong>';
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Status'),
                        'value' => function($model) {
                            if ($model->status == common\models\Payment::STATUS_PAID) {
                                return '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Tasdiqlangan</span>';
                            } elseif ($model->status == common\models\Payment::STATUS_FAILED) {
                                return '<span class="badge bg-danger"><i class="fas fa-times-circle"></i> Bekor qilingan</span>';
                            } else {
                                return '<span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Kutilmoqda</span>';
                            }
                        }
                    ],
                    [
                        'attribute' => 'receipt_file',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Chek'),
                        'value' => function($model) {
                            if ($model->receipt_file) {
                                // Rasm manzilini to'g'rilab olasiz (frontend/web/uploads/receipts kabi bo'lishi mumkin)
                                $url = Yii::$app->request->hostInfo . '/frontend/web/uploads/receipts/' . $model->receipt_file;
                                return Html::a('<i class="fas fa-image"></i> Ko\'rish', $url, [
                                    'class' => 'btn btn-sm btn-outline-info',
                                    'target' => '_blank', // Yangi oynada ochiladi
                                    'data-pjax' => '0'
                                ]);
                            }
                            return '<span class="text-muted small">Chek yo\'q</span>';
                        }
                    ],
                    [
                        'attribute' => 'payment_date',
                        'format' => ['date', 'php:d.m.Y'],
                        'label' => Yii::t('app', 'Date'),
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app', 'Actions'),
                        'template' => '{approve} {reject} {view} {update} {delete}',
                        'buttons' => [
                            'approve' => function ($url, $model) {
                                if ($model->status == common\models\Payment::STATUS_PENDING) {
                                    return Html::a('<i class="fas fa-check"></i>', ['approve', 'id' => $model->id], [
                                        'class' => 'btn btn-sm btn-success', 
                                        'title' => 'Tasdiqlash',
                                        'data-confirm' => 'Haqiqatan ham bu to\'lovni tasdiqlaysizmi?',
                                        'data-method' => 'post'
                                    ]);
                                }
                                return '';
                            },
                            'reject' => function ($url, $model) {
                                if ($model->status == common\models\Payment::STATUS_PENDING) {
                                    return Html::a('<i class="fas fa-times"></i>', ['reject', 'id' => $model->id], [
                                        'class' => 'btn btn-sm btn-warning', 
                                        'title' => 'Bekor qilish',
                                        'data-confirm' => 'To\'lovni bekor qilmoqchimisiz?',
                                        'data-method' => 'post'
                                    ]);
                                }
                                return '';
                            },
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info']);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-sm btn-primary']);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this payment?'),
                                    'data-method' => 'post',
                                ]);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>


<?php
// Bazadan joriy ma'lumotlarni olib kelamiz
$uzcardNum = \common\models\Setting::getValue('uzcard_number', '');
$uzcardName = \common\models\Setting::getValue('uzcard_name', '');
$humoNum = \common\models\Setting::getValue('humo_number', '');
$humoName = \common\models\Setting::getValue('humo_name', '');
$cashAddress = \common\models\Setting::getValue('cash_address', 'Toshkent shahar, Chilonzor tumani, 1-mavze'); // YANGLIK
?>

<div class="modal fade" id="cardSettingsModal" tabindex="-1" aria-labelledby="cardSettingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?= yii\helpers\Url::to(['payment/update-cards']) ?>" method="post">
          <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
          
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title fw-bold" id="cardSettingsModalLabel"><i class="fas fa-credit-card"></i> To'lov kartalarini sozlash</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          
          <div class="modal-body">
              <div class="alert alert-info py-2 small">
                  Bu yerda kiritilgan karta raqamlari o'quvchilarga to'lov sahifasida ko'rsatiladi.
              </div>

              <h6 class="text-primary mt-3 fw-bold"><i class="fas fa-credit-card"></i> UZCARD</h6>
              <div class="mb-3">
                  <label class="form-label text-muted small mb-1">Karta raqami</label>
                  <input type="text" name="uzcard_number" class="form-control" value="<?= Html::encode($uzcardNum) ?>" placeholder="8600 ...." required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted small mb-1">Karta egasining FISH</label>
                  <input type="text" name="uzcard_name" class="form-control" value="<?= Html::encode($uzcardName) ?>" placeholder="Ism familiya" required>
              </div>

              <hr>

              <h6 class="text-success mt-3 fw-bold"><i class="fas fa-credit-card"></i> HUMO</h6>
              <div class="mb-3">
                  <label class="form-label text-muted small mb-1">Karta raqami</label>
                  <input type="text" name="humo_number" class="form-control" value="<?= Html::encode($humoNum) ?>" placeholder="9860 ...." required>
              </div>
              <div class="mb-3">
                  <label class="form-label text-muted small mb-1">Karta egasining FISH</label>
                  <input type="text" name="humo_name" class="form-control" value="<?= Html::encode($humoName) ?>" placeholder="Ism familiya" required>
              </div>

              <hr>

              <h6 class="text-danger mt-3 fw-bold"><i class="fas fa-map-marker-alt"></i> NAQD TO'LOV MANZILI</h6>
              <div class="mb-3">
                  <label class="form-label text-muted small mb-1">O'quv markazi manzili va mo'ljal</label>
                  <textarea name="cash_address" class="form-control" rows="2" required><?= Html::encode($cashAddress) ?></textarea>
              </div>
          </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bekor qilish</button>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Saqlash</button>
          </div>
      </form>
    </div>
  </div>
</div>