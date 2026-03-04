<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = Yii::t('app', 'Enrollment') . ' #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Enrollments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="enrollment-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-clipboard-list"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure?'),
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
                        'attribute' => 'group_id',
                        'label' => Yii::t('app', 'Group'),
                        'value' => $model->group->name . ' (' . $model->group->course->name . ')',
                    ],
                    [
                        'attribute' => 'enrolled_on',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Enrolled On'),
                    ],
                    [
                        'attribute' => 'status',
                        'label' => Yii::t('app', 'Status'),
                        'value' => Yii::t('app', ucfirst($model->status)),
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