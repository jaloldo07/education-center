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
        <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Record Payment'), ['create'], ['class' => 'btn btn-success btn-hover']) ?>
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
                        'attribute' => 'payment_type',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Payment Type'),
                        'value' => function($model) {
                            $class = $model->payment_type == 'monthly' ? 'primary' : 'success';
                            $label = $model->payment_type == 'monthly' ? Yii::t('app', 'Monthly') : 
                                     ($model->payment_type == 'full' ? Yii::t('app', 'Full Payment') : Yii::t('app', ucfirst($model->payment_type)));
                            return '<span class="badge bg-' . $class . '">' . $label . '</span>';
                        }
                    ],
                    [
                        'attribute' => 'payment_date',
                        'format' => ['date', 'php:d.m.Y'],
                        'label' => Yii::t('app', 'Payment Date'),
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => Yii::t('app', 'Actions'),
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info', 'title' => Yii::t('app', 'View')]);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-sm btn-primary', 'title' => Yii::t('app', 'Update')]);
                            },
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => Yii::t('app', 'Delete'),
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