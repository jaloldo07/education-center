<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Students'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="student-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-user-graduate"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this student?'),
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
                        'attribute' => 'full_name',
                        'label' => Yii::t('app', 'Full Name'),
                    ],
                    'email:email',
                    [
                        'attribute' => 'phone',
                        'label' => Yii::t('app', 'Phone'),
                    ],
                    [
                        'attribute' => 'birth_date',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Birth Date'),
                    ],
                    [
                        'attribute' => 'address',
                        'format' => 'ntext',
                        'label' => Yii::t('app', 'Address'),
                    ],
                    [
                        'attribute' => 'enrolled_date',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Enrolled Date'),
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