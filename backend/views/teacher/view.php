<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Teachers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="teacher-view">
    <div class="d-flex justify-content-between mb-3">
        <h1><i class="fas fa-chalkboard-teacher"></i> <?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('<i class="fas fa-arrow-left"></i> ' . Yii::t('app', 'Back to List'), ['index'], ['class' => 'btn btn-secondary']) ?>
            <?= Html::a('<i class="fas fa-edit"></i> ' . Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-trash"></i> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data-confirm' => Yii::t('app', 'Are you sure you want to delete this teacher?'),
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
                    [
                        'attribute' => 'subject',
                        'label' => Yii::t('app', 'Subject'),
                    ],
                    [
                        'attribute' => 'experience_years',
                        'label' => Yii::t('app', 'Experience (Years)'),
                    ],
                    [
                        'attribute' => 'phone',
                        'label' => Yii::t('app', 'Phone'),
                    ],
                    'email:email',
                    [
                        'attribute' => 'bio',
                        'label' => Yii::t('app', 'Bio'),
                        'format' => 'ntext',
                    ],
                    [
                        'attribute' => 'rating',
                        'label' => Yii::t('app', 'Rating'),
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