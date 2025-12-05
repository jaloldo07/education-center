<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\EnrollmentApplication;

$this->title = 'Enrollment Applications';
$this->params['breadcrumbs'][] = $this->title;
?>


<style>
    .enrollment-application-index table thead a {
        color: #fff !important;
        font-weight: 600;
        text-decoration: none !important;
    }

    .enrollment-application-index table thead a:hover {
        color: #e8e8e8 !important;
        text-decoration: none !important;
    }
</style>


<div class="enrollment-application-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-clipboard-list"></i> <?= Html::encode($this->title) ?></h1>
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
                        'attribute' => 'student_id',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(
                                '<i class="fas fa-user text-primary"></i> ' . Html::encode($model->student->full_name),
                                ['/enrollment-application/view', 'id' => $model->id],
                                ['style' => 'text-decoration: none; font-weight: bold;']
                            );
                        },
                    ],
                    [
                        'attribute' => 'course_id',
                        'value' => function ($model) {
                            return $model->course->name . ' (' . $model->course->type . ')';
                        }
                    ],
                    [
                        'attribute' => 'group_id',
                        'value' => 'group.name',
                    ],
                    [
                        'attribute' => 'status',
                        'format' => 'raw',
                        'value' => function ($model) {
                            $class = EnrollmentApplication::getStatusBadgeClass($model->status);
                            return '<span class="badge bg-' . $class . '">' . ucfirst($model->status) . '</span>';
                        },
                        'filter' => EnrollmentApplication::getStatusOptions(),
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => ['date', 'php:Y-m-d H:i'],
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'delete' => function ($url) {
                                return Html::a('<i class="fas fa-trash"></i>', $url, [
                                    'class' => 'btn btn-sm btn-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ]);
                            },
                            'view' => function ($url) {
                                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-info']);
                            },
                        ],
                    ],
                ],
            ]); ?>
        </div>
    </div>
</div>