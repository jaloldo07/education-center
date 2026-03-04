<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="course-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-book"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this course?'),
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
                        'attribute' => 'name',
                        'label' => Yii::t('app', 'Name'),
                    ],
                    [
                        'attribute' => 'description',
                        'label' => Yii::t('app', 'Description'),
                        'format' => 'ntext',
                    ],
                    [
                        'attribute' => 'duration',
                        'label' => Yii::t('app', 'Duration'),
                        'value' => $model->duration . ' ' . Yii::t('app', 'months'),
                    ],
                    [
                        'attribute' => 'type',
                        'label' => Yii::t('app', 'Type'),
                        'format' => 'raw',
                        'value' => '<span class="badge bg-' . ($model->type === 'free' ? 'success' : 'warning') . ' fs-6">' . strtoupper($model->type) . '</span>',
                    ],
                    [
                        'attribute' => 'price',
                        'label' => Yii::t('app', 'Price'),
                        'value' => number_format($model->price, 0) . ' ' . Yii::t('app', 'UZS'),
                    ],
                    [
                        'attribute' => 'teacher_id',
                        'label' => Yii::t('app', 'Teacher'),
                        'value' => $model->teacher->full_name ?? Yii::t('app', 'N/A'),
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Created At'),
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'updated_at',
                        'label' => Yii::t('app', 'Updated At'),
                        'format' => 'datetime',
                    ],
                ],
            ]) ?>
        </div>
    </div>
</div>