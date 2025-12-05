<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Enrollment #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Enrollments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="enrollment-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-clipboard-list"></i> <?= Html::encode($this->title) ?></h1>
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
                        'attribute' => 'group_id',
                        'value' => $model->group->name . ' (' . $model->group->course->name . ')',
                    ],
                    'enrolled_on:date',
                    'status',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        </div>
    </div>
</div>