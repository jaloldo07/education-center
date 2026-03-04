<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->title = Yii::t('app', 'Payment') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="payment-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-money-bill-wave"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this payment?'),
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
                        'label' => Yii::t('app', 'Student'),
                        'value' => $model->student->full_name,
                    ],
                    [
                        'attribute' => 'course_id',
                        'label' => Yii::t('app', 'Course'),
                        'value' => $model->course->name,
                    ],
                    [
                        'attribute' => 'amount',
                        'label' => Yii::t('app', 'Amount'),
                        'format' => 'raw',
                        'value' => '<strong>' . number_format($model->amount, 0, '.', ' ') . ' ' . Yii::t('app', 'UZS') . '</strong>',
                    ],
                    [
                        'attribute' => 'payment_date',
                        'format' => ['date', 'php:d.m.Y'],
                        'label' => Yii::t('app', 'Payment Date'),
                    ],
                    [
                        'attribute' => 'payment_type',
                        'label' => Yii::t('app', 'Payment Type'),
                        'value' => function($model) {
                             return $model->payment_type == 'monthly' ? Yii::t('app', 'Monthly Fee') : 
                                   ($model->payment_type == 'full' ? Yii::t('app', 'Full Payment') : Yii::t('app', ucfirst($model->payment_type)));
                        }
                    ],
                    [
                        'attribute' => 'payment_method',
                        'label' => Yii::t('app', 'Method'),
                        'value' => function($model) {
                            return Yii::t('app', ucfirst(str_replace('_', ' ', $model->payment_method)));
                        }
                    ],
                    [
                        'attribute' => 'note',
                        'label' => Yii::t('app', 'Note'),
                        'format' => 'ntext',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'label' => Yii::t('app', 'Created At'),
                    ],
                    [
                        'attribute' => 'updated_at',
                        'format' => 'datetime',
                        'label' => Yii::t('app', 'Updated At'),
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>