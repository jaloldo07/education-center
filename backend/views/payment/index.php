<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;

// Calculate totals
$totalAmount = 0;
foreach ($dataProvider->models as $payment) {
    $totalAmount += $payment->amount;
}
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
        <?= Html::a('<i class="fas fa-plus"></i> Record Payment', ['create'], ['class' => 'btn btn-success btn-hover']) ?>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header text-white" style="background: linear-gradient(90deg, #5e72cc, #59558f);">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Filter by Course</h5>
        </div>
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Select Course:</label>
                    <?= Html::dropDownList('course_id', $selectedCourse, $courses, [
                        'class' => 'form-control form-control-lg',
                        'prompt' => '-- All Courses --',
                        'id' => 'course-filter'
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                    <?php if ($selectedCourse): ?>
                        <?= Html::a('<i class="fas fa-times"></i> Clear', ['index'], ['class' => 'btn btn-secondary btn-lg w-100 mt-2']) ?>
                    <?php endif; ?>
                </div>
            </form>
            
            <?php if ($selectedCourse): ?>
                <div class="alert alert-info mt-3 mb-0">
                    <i class="fas fa-info-circle"></i> Showing payments for: 
                    <strong><?= Html::encode($courses[$selectedCourse]) ?></strong>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Summary Card -->
    <?php if ($dataProvider->totalCount > 0): ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow bg-success text-white">
                <div class="card-body">
                    <h6 class="text-uppercase">Total Payments</h6>
                    <h3><?= $dataProvider->totalCount ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow bg-primary text-white">
                <div class="card-body">
                    <h6 class="text-uppercase">Total Amount</h6>
                    <h3><?= number_format($totalAmount, 0) ?> UZS</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow bg-info text-white">
                <div class="card-body">
                    <h6 class="text-uppercase">Average Payment</h6>
                    <h3><?= $dataProvider->totalCount > 0 ? number_format($totalAmount / $dataProvider->totalCount, 0) : 0 ?> UZS</h3>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Payments Table -->
    <div class="card shadow">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    [
                        'attribute' => 'student_id',
                        'value' => 'student.full_name',
                        'label' => 'Student',
                    ],
                    [
                        'attribute' => 'course_id',
                        'value' => 'course.name',
                        'label' => 'Course',
                    ],
                    [
                        'attribute' => 'amount',
                        'format' => 'raw',
                        'value' => function($model) {
                            return '<strong class="text-success">' . number_format($model->amount, 0) . ' UZS</strong>';
                        }
                    ],
                    [
                        'attribute' => 'payment_type',
                        'format' => 'raw',
                        'value' => function($model) {
                            $class = $model->payment_type == 'monthly' ? 'primary' : 'success';
                            return '<span class="badge bg-' . $class . '">' . ucfirst($model->payment_type) . '</span>';
                        }
                    ],
                    [
                        'attribute' => 'payment_date',
                        'format' => ['date', 'php:Y-m-d'],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {update} {delete}',
                        'buttons' => [
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info', 'title' => 'View']);
                            },
                            'update' => function ($url) {
                                return Html::a('<i class="fas fa-edit"></i>', $url, ['class' => 'btn btn-sm btn-primary', 'title' => 'Update']);
                            },
                            'format' => 'raw',
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'title' => 'Delete',
                                    'data-confirm' => 'Are you sure?',
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