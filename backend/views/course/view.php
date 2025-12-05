<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="course-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-book"></i> <?= Html::encode($this->title) ?></h1>
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
                    'name',
                    'description:ntext',
                    'duration',
                    [
                        'attribute' => 'type',
                        'format' => 'raw',
                        'value' => '<span class="badge bg-' . ($model->type === 'free' ? 'success' : 'warning') . ' fs-6">' . strtoupper($model->type) . '</span>',
                    ],
                    [
                        'attribute' => 'price',
                        'value' => number_format($model->price, 0) . ' UZS',
                    ],
                    [
                        'attribute' => 'teacher_id',
                        'value' => $model->teacher->full_name,
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>