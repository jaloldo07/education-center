<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Payment #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-money-bill-wave"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => 'Are you sure?',
                'data-method' => 'post',
            ]) ?>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id',
                    [
                        'attribute' => 'student_id',
                        'value' => $model->student->full_name,
                    ],
                    [
                        'attribute' => 'course_id',
                        'value' => $model->course->name,
                    ],
                    [
                        'attribute' => 'amount',
                        'value' => number_format($model->amount, 0) . ' UZS',
                    ],
                    'payment_date:date',
                    'payment_type',
                    'note:ntext',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>