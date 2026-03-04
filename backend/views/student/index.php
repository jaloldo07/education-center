<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Students');
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .student-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .student-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>

<div class="student-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-user-graduate"></i> <?= Html::encode($this->title) ?></h1>
        <?= Html::a('<i class="fas fa-plus"></i> ' . Yii::t('app', 'Add Student'), ['create'], ['class' => 'btn btn-success btn-hover']) ?>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-hover'],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    [
                        'attribute' => 'full_name',
                        'format' => 'raw',
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-user text-primary"></i> ' . Html::encode($model->full_name),
                                ['/student/view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    [
                        'attribute' => 'birth_date',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Birth Date'),
                    ],
                    [
                        'attribute' => 'enrolled_date',
                        'format' => ['date', 'php:Y-m-d'],
                        'label' => Yii::t('app', 'Enrolled Date'),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
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
                                    'data-confirm' => Yii::t('app', 'Are you sure you want to delete this student?'),
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