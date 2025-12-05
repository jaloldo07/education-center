<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Students';
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
        <?= Html::a('<i class="fas fa-plus"></i> Add Student', ['create'], ['class' => 'btn btn-success btn-hover']) ?>
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
                    ],
                    [
                        'attribute' => 'enrolled_date',
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